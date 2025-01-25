<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CommandInterface;
use App\Domain\Basket\Basket;
use App\Domain\Basket\Event\BasketCreated;
use App\Infrastructure\Persistence\RedisEventStore;

class CreateBasketHandler implements CommandHandlerInterface
{
    public function __construct(private RedisEventStore $eventStore)
    {
    }

    public function __invoke(CommandInterface $command): void
    {
        $coupon = Basket::create();
        $event = new BasketCreated($command->getId(), $command->getPrice());
        $coupon->applyCreate($event);

        $this->eventStore->save($event, $event->id);
    }
}
