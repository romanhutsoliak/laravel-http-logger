<?php

namespace Hutsoliak\HttpLogger\Helpers;

class LoggerHelper
{
    /**
     * Check if Logger is enabled
     *
     * @return bool
     */
    public static function isServiceEnabled(): bool
    {
        $isEnabled = false;
        if (app()->environment('local')) {
            $isEnabled = true;
        }
        // can be overwritted in services.http_logger_enabled as true or false for
        $servicesHttp_logger_enabled = config('services.http_logger.enabled');
        if (!is_null($servicesHttp_logger_enabled) && is_bool($servicesHttp_logger_enabled)) {
            $isEnabled = $servicesHttp_logger_enabled;
        }
        return $isEnabled;
    }
}