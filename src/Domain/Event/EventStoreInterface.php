<?php
declare(strict_types=1);

namespace App\Domain\Event;

interface EventStoreInterface
{
    public function save(Event $event, int $aggregateId): void;

    public function getEvents(int $aggregateId): array;
}
