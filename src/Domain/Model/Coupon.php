<?php

declare(strict_types=1);

namespace App\Domain\Model;
use App\Domain\Coupon\Event\CouponApplied;
use App\Domain\Coupon\Event\CouponCreated;
use App\Domain\Coupon\Event\CouponRevoked;
use App\Domain\Event\Event;

class Coupon
{
    private int $id;
    private float $discountAmount;
    private int $usageCount;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $revokedAt;

    public static function create(): Coupon
    {
        return new self();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function getUsageCount(): int
    {
        return $this->usageCount;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRevokedAt(): ?\DateTimeImmutable
    {
        return $this->revokedAt;
    }

    public function rejeu(Event $event)
    {
        if ($event instanceof CouponRevoked) {
            $this->applyRevoke($event);
        }

        if ($event instanceof CouponCreated) {
            $this->applyCreate($event);
        }

        if ($event instanceof CouponApplied) {
            $this->applyCoupon($event);
        }
    }

    public function applyRevoke(CouponRevoked $event): void
    {
        $this->revokedAt = $event->getRevokedAt();
    }

    public function applyCreate(CouponCreated $event): void
    {
        $this->id = $event->getId();
        $this->discountAmount = $event->getAmount();
        $this->createdAt = $event->getCreatedAt();
        $this->revokedAt = null;
        $this->usageCount = 0;
    }

    public function applyCoupon(CouponApplied $event): void
    {
        if ($this->revokedAt !== null) {
            throw new \Exception('!!!!!!SAVAGE REJECTION !!!!!!: Coupon has been revoked and cannot be used');
        }

        $this->usageCount++;
    }
}
