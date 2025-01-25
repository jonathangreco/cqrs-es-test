<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Basket\ValueObject\BasketPrice;

class CreateBasket implements CommandInterface
{
    private int $id;

    private float $price;

    public function __construct(int $id, float $amount)
    {
        $this->id = $id;
        $this->price = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
