<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CommandInterface;
use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Event\CouponRevoked;
use App\Infrastructure\Persistence\RedisEventStore;

class RevokeCouponHandler implements CommandHandlerInterface
{
    public function __construct(private RedisEventStore $eventStore)
    {
    }

    public function __invoke(CommandInterface $command): void
    {
        $events = $this->eventStore->getEvents($command->getIdCoupon());
        $coupon = Coupon::create();

        foreach ($events as $event) {
            $eventClass = $event['class']::fromArray($event);
            $coupon->rejeu($eventClass);
        }

        if ($coupon->getRevokedAt() !== null) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Le coupon a déjà été révoqué.");
        }

        $event = new CouponRevoked($command->getIdCoupon(), new \DateTimeImmutable());
        $coupon->applyRevoke($event);

        $this->eventStore->save($event, $event->getId());
    }
}
