<?php

declare(strict_types=1);

namespace App\UI\Web\Controller;

use App\Domain\Coupon\Event\CouponAppliedEvent;
use App\Infrastructure\Persistence\RedisEventStore;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private RedisEventStore $eventStore)
    {
    }

    #[Route('/apply-coupon', methods: ['POST'])]
    public function applyCoupon(Request $request): JsonResponse
    {
        $event = new CouponAppliedEvent($request->get('id'), (float) $request->get('discount'));
        $this->eventStore->save($event, 'coupon-' . $request->get('id'));
        $events = $this->eventStore->getEvents('coupon-' . $request->get('id'));

        return new JsonResponse($events);
    }
}
