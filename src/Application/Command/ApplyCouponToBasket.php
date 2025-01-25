<?php

declare(strict_types=1);

namespace App\Application\Command;

class ApplyCouponToBasket implements CommandInterface
{
    private int $idCoupon;

    private int $idBasket;

    public function __construct(int $idCoupon, int $idBasket)
    {
        $this->idCoupon = $idCoupon;
        $this->idBasket = $idBasket;
    }

    public function getIdCoupon(): int
    {
        return $this->idCoupon;
    }

    public function getIdBasket(): int
    {
        return $this->idBasket;
    }
}
