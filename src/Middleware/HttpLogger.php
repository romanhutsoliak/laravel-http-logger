<?php

namespace Hutsoliak\HttpLogger\Middleware;

use Carbon\Carbon;
use Closure;
use Hutsoliak\HttpLogger\Models\LogsHttp;
use Hutsoliak\HttpLogger\Storage\ListenerResponseStorage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpLogger
{
    protected static array $httpLoggerTime = [];

    protected static array $ignoreUrls = [
        '^/_debugbar/',
    ];

    public function __construct(
        protected ListenerResponseStorage $storage,
    ){}

    public function handle(Request $request, Closure $next)
    {
        // php processing time
        if (config('services.http_logger.enabled')) {
            static::$httpLoggerTime[$this->getKey()] = microtime(true);
        }

        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        if (!config('services.http_logger.enabled')) {
            return;
        }

        $method = strtoupper($request->method());
        if ($method == 'OPTIONS') {
            return;
        }

        if (!empty(static::$ignoreUrls)) {
            foreach (static::$ignoreUrls as $ignoreUrl) {
                if (preg_match('#' . $ignoreUrl . '#', $request->getPathInfo())) {
                    return;
                }
            }
        }

        if ($response instanceof JsonResponse) {
            $responseContent = $response->content();
        } else {
            $responseContent = $response->getContent();
        }
        if (is_string($responseContent) && preg_match('#^(?:\{|\[).+#', $responseContent)) {
            $responseContent = json_decode($responseContent, true);
        } else {
            $responseContent = $responseContent ?: null;
        }

        $headers = [
            'response' => [],
            'request' => [],
        ];
        foreach ($request->headers as $key => $header) {
            $headers['request'][$key] = is_array($header) && count($header) == 1 ? $header[0] : $header;
        }
        foreach ($response->headers as $key => $header) {
            $headers['response'][$key] = is_array($header) && count($header) == 1 ? $header[0] : $header;
        }

        $time = microtime(true) - (static::$httpLoggerTime[$this->getKey()] ?? microtime(true));
        $logHttp = LogsHttp::create([
            'status' => $response->status(),
            'method' => $method,
            'url' => $request->getPathInfo() . ($request->getQueryString() ? '?' . $request->getQueryString() : ''),
            'request' => $request->all() ?: null,
            'response' => $responseContent,
            'headers' => $headers,
            'cookies' => $request->cookie() ?: null,
            'time' => (int)($time * 1000),
            'created_at' => Carbon::now()->subSeconds($time)->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        // store responses to db, to get correct responses order in the db table
        $storedResponses = $this->storage->getResponses();
        foreach ($storedResponses as $storedResponse) {
            $storedResponse['parent_id'] = $logHttp->id;
            LogsHttp::create($storedResponse);
        }
    }

    private function getKey()
    {
        return 't_' . md5(request()->getPathInfo() . print_r(request()->all(), true));
    }
}