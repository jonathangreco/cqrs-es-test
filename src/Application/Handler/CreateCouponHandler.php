<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CommandInterface;
use App\Domain\Coupon\Event\CouponCreated;
use App\Domain\Model\Coupon;
use App\Infrastructure\Persistence\RedisEventStore;

class CreateCouponHandler implements CommandHandlerInterface
{
    public function __construct(private RedisEventStore $eventStore)
    {
    }

    public function __invoke(CommandInterface $command): void
    {
        $coupon = Coupon::create();
        $event = new CouponCreated($command->getId(), $command->getAmount(), new \DateTimeImmutable());
        $coupon->applyCreate($event);

        $this->eventStore->save($event, $event->getId());
    }
}
