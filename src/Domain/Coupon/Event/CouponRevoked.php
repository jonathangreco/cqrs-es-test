<?php

declare(strict_types=1);

namespace App\Domain\Coupon\Event;

use App\Domain\Event\Event;

class CouponRevoked implements Event
{
    public string $state = 'REVOKED';

    public string $key;

    public string $class;

    public function __construct(public int $id, public \DateTimeImmutable $revokedAt)
    {
        $this->key = 'coupon:';
        $this->class = get_class($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRevokedAt(): \DateTimeImmutable
    {
        return $this->revokedAt;
    }

    public static function fromArray(array $event): self
    {
        return new self($event['id'], new \DateTimeImmutable($event['revokedAt']['date']));
    }
}
