<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Coupon\ValueObject\Amount;

class ApplyCoupon implements CommandInterface
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
