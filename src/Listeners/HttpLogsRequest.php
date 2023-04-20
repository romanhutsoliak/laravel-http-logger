<?php

namespace Hutsoliak\HttpLogger\Listeners;

use Hutsoliak\HttpLogger\Managers\LoggerHttpManager;

class HttpLogsRequest
{
    public static array $httpLoggerTime = [];

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (! config('app.http_logger_enabled')) {
            return;
        }

        // php processing time
        static::setHttpLoggerTime($event->request->url(), $event->request->data());
    }

    public static function setHttpLoggerTime(string $url, $requestData): void
    {
        static::$httpLoggerTime[static::getTimeKey($url, $requestData)] = microtime(true);
    }

    public static function getHttpLoggerTime(string $url, $requestData): int
    {
        return static::$httpLoggerTime[static::getTimeKey($url, $requestData)] ?? microtime(true);
    }

    public static function getTimeKey(string $url, $requestData): string
    {
        return 't_'.md5($url.print_r($requestData, true));
    }
}