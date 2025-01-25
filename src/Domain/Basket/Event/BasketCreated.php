<?php

declare(strict_types=1);

namespace App\Domain\Basket\Event;

use App\Domain\Event\Event;

class BasketCreated implements Event
{
    public string $state = 'CREATED';

    public string $class;

    public string $key;

    public function __construct(public int $id, public float $price)
    {
        $this->class = get_class($this);
        $this->key = 'basket:';
    }

    public static function fromArray(array $event): self
    {
        return new self($event['id'], $event['price']);
    }
}
