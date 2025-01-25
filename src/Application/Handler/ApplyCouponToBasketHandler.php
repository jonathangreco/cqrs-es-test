<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CommandInterface;
use App\Domain\Basket\Basket;
use App\Domain\Basket\Event\BasketCouponApplied;
use App\Domain\Basket\ValueObject\BasketPrice;
use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Event\CouponApplied;
use App\Infrastructure\Persistence\RedisEventStore;

class ApplyCouponToBasketHandler implements CommandHandlerInterface
{
    public function __construct(private RedisEventStore $eventStore)
    {
    }

    public function __invoke(CommandInterface $command): void
    {
        try {
            $coupon = $this->checkCouponEligibility($command->getIdCoupon());
            $basket = $this->checkBasketEligibility($command->getIdBasket());
        } catch (\RuntimeException $exception) {
            throw $exception;
        }

        $event = new CouponApplied($command->getIdCoupon(), $coupon->getUsageCount());
        $coupon->applyCoupon($event);
        $this->eventStore->changePrefix('coupon:');
        $this->eventStore->save($event, $event->getId());

        $event = new BasketCouponApplied($command->getIdBasket(), $basket->getPrice(), $coupon->getDiscountAmount(), $coupon->getId());
        $basket->applyCoupon($event);
        $this->eventStore->changePrefix('basket:');
        $this->eventStore->save($event, $event->id);
    }

    protected function checkCouponEligibility(int $idCoupon): Coupon
    {
        $this->eventStore->changePrefix('coupon:');
        $couponEvents = $this->eventStore->getEvents($idCoupon);
        $coupon = Coupon::create();

        foreach ($couponEvents as $eventStream) {
            $eventClass = $eventStream['class']::fromArray($eventStream);
            $coupon->rejeu($eventClass);
        }

        if ($coupon->getRevokedAt() !== null) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Le coupon a été révoqué. Impossible d'appliquer le coupon.");
        }

        if ($coupon->getUsageCount() >= 10) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Nombre maximum d'utilisation atteint. Coupon inutilisable");
        }

        return $coupon;
    }

    protected function checkBasketEligibility(int $idBasket): Basket
    {
        $this->eventStore->changePrefix('basket:');
        $basketEvents = $this->eventStore->getEvents($idBasket);
        $basket = Basket::create();

        foreach ($basketEvents as $eventStream) {
            $eventClass = $eventStream['class']::fromArray($eventStream);
            $basket->rejeu($eventClass);
        }

        if ($basket->getCouponId() !== null) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Le coupon a été appliqué une fois");
        }

        if (! BasketPrice::init($basket->getPrice())) {
            throw new \RuntimeException("!!!SAVAGE REJECTION !!! Le panier n'est pas éligible, son prix est trop bas");
        }

        return $basket;
    }
}
