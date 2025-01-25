<?php

declare(strict_types=1);

namespace App\Domain\Coupon\ValueObject;

class Amount
{
    private float $amount;

    public static function init(float $amount): self
    {
        $object = new self();
        $object->amount = $amount;

        return $object;
    }

    public function amount(): float
    {
        return $this->amount;
    }
}
