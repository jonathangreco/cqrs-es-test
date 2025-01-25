<?php

declare(strict_types=1);

namespace App\Domain\Coupon\Event;

use App\Domain\Coupon\ValueObject\Amount;
use App\Domain\Event\Event;

class CouponCreated implements Event
{
    public string $state = 'CREATED';

    public string $class;

    public string $key;

    public float $amount;

    public function __construct(public int $id, Amount $amount, public \DateTimeImmutable $createdAt)
    {
        $this->class = get_class($this);
        $this->key = 'coupon:';
        $this->amount = $amount->amount();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }


    public static function fromArray(array $event): self
    {
        $amount = Amount::init($event['amount']);

        return new self($event['id'], $amount, new \DateTimeImmutable($event['createdAt']['date']));
    }
}
