<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Event\Event;
use App\Domain\Event\EventStoreInterface;

class RedisEventStore implements EventStoreInterface
{
    private \Redis $redis;

    public function __construct(private string $eventPrefix = 'event:')
    {
        $this->redis = new \Redis();
        $this->redis->connect('redis-danim');
    }

    public function save(Event $event, int $aggregateId): void
    {
        $eventKey = $this->eventPrefix . $aggregateId;

        $this->redis->rPush($eventKey, json_encode($event, JSON_THROW_ON_ERROR));
    }

    public function exists(int $id): bool|int|\Redis
    {
        return $this->redis->exists($this->eventPrefix . $id);
    }
    
    
    public function getEvents(int $aggregateId): array
    {
        $eventKey = $this->eventPrefix . $aggregateId;
        $events = $this->redis->lRange($eventKey, 0, -1);

        return array_map(function ($event) {
            return json_decode($event, true, 512, JSON_THROW_ON_ERROR);
        }, $events);
    }

    public function changePrefix(string $string): void
    {
        $this->eventPrefix = $string;
    }
}
