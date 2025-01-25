<?php

declare(strict_types=1);

namespace App\Domain\Basket\Event;

use App\Domain\Basket\ValueObject\BasketPricePositive;
use App\Domain\Coupon\Coupon;
use App\Domain\Event\Event;

class BasketCouponApplied implements Event
{
    public string $state = 'BASKET COUPON APPLIED';

    public string $key;

    public float $price;

    public string $class;

    public float $amountCoupon;

    public float $amountBasket;

    public int $idCoupon;

    public function __construct(public int $id, float $amountBasket, $amountCoupon, $idCoupon)
    {
        $this->key = 'basket:';
        $this->amountBasket = $amountBasket;
        $this->amountCoupon = $amountCoupon;
        $this->price = BasketPricePositive::init($amountBasket, $amountCoupon)->price();
        $this->idCoupon = $idCoupon;
        $this->class = get_class($this);
    }

    public static function fromArray(array $event): self
    {
        return new self($event['id'], $event['amountBasket'], $event['amountCoupon'], $event['idCoupon']);
    }
}
