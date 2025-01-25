<?php

declare(strict_types=1);

namespace App\Domain\Coupon\Event;

use App\Domain\Coupon\ValueObject\Amount;
use App\Domain\Event\Event;

class CouponApplied implements Event
{
    public string $state = 'APPLIED';

    public string $key;

    public int $currentUsageCount;

    public string $class;

    public function __construct(public int $id, int $currentUsageCount)
    {
        $this->key = 'coupon:';
        $this->currentUsageCount = ++$currentUsageCount;
        $this->class = get_class($this);
    }

    public static function fromArray(array $event): self
    {
        return new self($event['id'], $event['currentUsageCount']);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
