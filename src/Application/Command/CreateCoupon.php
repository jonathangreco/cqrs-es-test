<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Coupon\ValueObject\Amount;

class CreateCoupon implements CommandInterface
{
    private int $id;

    private Amount $amount;

    public function __construct(int $id, float $amount)
    {
        $this->id = $id;
        $this->amount = Amount::init($amount);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }
}
