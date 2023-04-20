<?php

namespace Hutsoliak\HttpLogger\Storage;

/**
 * Class for store responses from Http event listener
 * Hutsoliak\HttpLogger\Listeners\HttpLogsResponse
 */
class ListenerResponseStorage
{
    protected array $responses = [];

    public function addResponse(array $logsHttpAttributes): void
    {
        $this->responses[] = $logsHttpAttributes;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }
}