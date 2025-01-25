<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CommandInterface;
use App\Domain\Coupon\Event\CouponApplied;
use App\Domain\Model\Coupon;
use App\Infrastructure\Persistence\RedisEventStore;

class ApplyCouponHandler implements CommandHandlerInterface
{
    public function __construct(private RedisEventStore $eventStore)
    {
    }

    public function __invoke(CommandInterface $command): void
    {
        $events = $this->eventStore->getEvents($command->getId());
        $coupon = Coupon::create();

        foreach ($events as $event) {
            $eventClass = $event['class']::fromArray($event);
            $coupon->rejeu($eventClass);
        }

        if ($coupon->getRevokedAt() !== null) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Le coupon a été révoqué. Impossible d'appliquer le coupon.");
        }

        if ($coupon->getUsageCount() >= 10) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Nombre maximum d'utilisation atteint. Coupon inutilisable");
        }

        $event = new CouponApplied($command->getId(), $coupon->getUsageCount());
        $coupon->applyCoupon($event);

        $this->eventStore->save($event, $event->getId());
    }
}
