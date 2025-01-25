<?php

declare(strict_types=1);

namespace App\Domain\Basket;

use App\Domain\Basket\ValueObject\BasketPrice;

class Basket
{
    private int $id;

    private float $price;

    private ?int $couponId;

    private ?float $amountCoupon;

    public static function create(): Basket
    {
        return new self();
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = BasketPrice::init($price)->price();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCouponId(): ?int
    {
        return $this->couponId;
    }

    public function getAmountCoupon(): ?float
    {
        return $this->amountCoupon;
    }

    public function rejeu($eventClass): void
    {
        if ($eventClass instanceof Event\BasketCreated) {
            $this->applyCreate($eventClass);
        }

        if ($eventClass instanceof Event\BasketCouponApplied) {
            $this->applyCoupon($eventClass);
        }
    }

    public function applyCreate(Event\BasketCreated $event)
    {
        $this->id = $event->id;
        $this->price = $event->price;
        $this->couponId = null;
        $this->amountCoupon = null;
    }

    public function applyCoupon(Event\BasketCouponApplied $event)
    {
        $this->price = $event->price;
        $this->couponId = $event->idCoupon;
        $this->amountCoupon = $event->amountCoupon;
    }
}
