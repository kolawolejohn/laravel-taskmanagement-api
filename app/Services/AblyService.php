<?php

namespace App\Services;

use Ably\AblyRest;
use Illuminate\Support\Facades\Log;

class AblyService
{
    protected $ably;
    protected $channel;

    public function __construct()
    {
        $apiKey = config('services.ably.key');
        $this->channel = config('services.ably.channel', 'tasks');

        $this->ably = new AblyRest($apiKey);
    }

    public function publish(string $event, array $data): void
    {
        try {
            $this->ably->channels->get($this->channel)->publish($event, $data);
        } catch (\Throwable $e) {
            Log::error("AblyService publish failed: " . $e->getMessage());
            throw $e;
        }
    }
}
