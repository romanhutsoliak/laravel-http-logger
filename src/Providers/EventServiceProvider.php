<?php

namespace Hutsoliak\HttpLogger\Providers;

use Hutsoliak\HttpLogger\Listeners\HttpLogsRequest;
use Hutsoliak\HttpLogger\Listeners\HttpLogsResponse;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Http\Client\Events\RequestSending;
use Illuminate\Http\Client\Events\ResponseReceived;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RequestSending::class => [
            HttpLogsRequest::class,
        ],
        ResponseReceived::class => [
            HttpLogsResponse::class,
        ],
    ];
}
