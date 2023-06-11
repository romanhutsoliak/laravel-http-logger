<?php

namespace Hutsoliak\HttpLogger\Listeners;

use Carbon\Carbon;
use Hutsoliak\HttpLogger\Helpers\LoggerHelper;
use Hutsoliak\HttpLogger\Managers\LoggerHttpManager;
use Hutsoliak\HttpLogger\Storage\ListenerResponseStorage;

class HttpLogsResponse
{
    protected static array $ignoreUrls = [
        '^/_debugbar/',
    ];

    public function __construct(
        protected ListenerResponseStorage $storage,
    ){}

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (!LoggerHelper::isServiceEnabled()) {
            return;
        }

        $method = strtoupper($event->request->method());
        if ($method == 'OPTIONS') {
            return;
        }

        $ignoreUrls = static::$ignoreUrls;
        $configIgnoreUrls = config('services.http_logger.ignoreUrls');
        if (!empty($configIgnoreUrls) && is_array($configIgnoreUrls)) {
            $ignoreUrls = array_merge($ignoreUrls, $configIgnoreUrls);
        }
        foreach ($ignoreUrls as $ignoreUrl) {
            if (preg_match('#'.$ignoreUrl.'#i', $event->request->url())) {
                return;
            }
        }

        $headers = [
            'response' => [],
            'request' => [],
        ];
        foreach ($event->request->headers() as $key => $header) {
            $headers['request'][$key] = is_array($header) && count($header) == 1 ? $header[0] : $header;
        }
        foreach ($event->response->headers() as $key => $header) {
            $headers['response'][$key] = is_array($header) && count($header) == 1 ? $header[0] : $header;
        }

        $startTimeMicroseconds = HttpLogsRequest::getHttpLoggerTime($event->request->url(), $event->request->data());
        $time = microtime(true) - $startTimeMicroseconds;

        $this->storage->addResponse([
            'status' => $event->response->status(),
            'method' => $method,
            'url' => $event->request->url(),
            'request' => $event->request->data() ?: null,
            'response' => $event->response->json() ?: $event->response->body() ?: null,
            'headers' => $headers,
            'time' => (int)($time * 1000),
            'created_at' => Carbon::now()->subSeconds($time)->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}