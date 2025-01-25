<?php

declare(strict_types=1);

namespace App\Application\Command;

class RevokeCoupon implements CommandInterface
{
    public function __construct(private int $idCoupon)
    {
    }

    public function getIdCoupon(): int
    {
        return $this->idCoupon;
    }
}
