<?php

declare(strict_types=1);

namespace App\Domain\Basket\ValueObject;

use App\Domain\Basket\Exception\DomainLogicException;

class BasketPrice
{
    private float $price;

    public static function init(float $amount): self
    {
        $object = new self();

        if (! $object->isValid($amount)) {
            throw DomainLogicException::invalidBasketPrice('"!!!SAVAGE REJECTION !!! Le panier à un montant inéligible pour un coupon');
        }

        $object->price = $amount;

        return $object;
    }

    public function price(): float
    {
        return $this->price;
    }

    private function isValid(float $amount): bool
    {
        return $amount > 50;
    }
}
