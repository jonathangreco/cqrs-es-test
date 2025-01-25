<?php

declare(strict_types=1);

namespace App\Domain\Basket\ValueObject;

use App\Domain\Basket\Exception\DomainLogicException;

class BasketPricePositive
{
    private float $price;

    public static function init(float $basketAmount, float $coupon): self
    {
        $object = new self();

        if (! $object->isValid($basketAmount, $coupon)) {
            throw DomainLogicException::invalidBasketPrice('"!!!SAVAGE REJECTION !!! Le coupon à un montant trop élevé en rapport au prix du panier');
        }

        $object->price = $basketAmount - $coupon;

        return $object;
    }

    public function price(): float
    {
        return $this->price;
    }

    private function isValid(float $basketAmount, float $coupon): bool
    {
        return ($basketAmount - $coupon) > 0;
    }
}
