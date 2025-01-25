<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Command\ApplyCouponToBasket;
use App\Application\Command\CommandInterface;
use App\Application\Command\CreateBasket;
use App\Application\Command\CreateCoupon;
use App\Application\Command\RevokeCoupon;
use App\Application\Handler\ApplyCouponToBasketHandler;
use App\Application\Handler\CommandHandlerInterface;
use App\Application\Handler\CreateBasketHandler;
use App\Application\Handler\CreateCouponHandler;
use App\Application\Handler\RevokeCouponHandler;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class CommandBus implements CommandBusInterface, ServiceSubscriberInterface
{
    private array $handlers = [];

    public function __construct(private ContainerInterface $serviceLocator)
    {
    }

    public static function getSubscribedServices(): array
    {
        return [
            CreateCoupon::class => CreateCouponHandler::class,
            RevokeCoupon::class => RevokeCouponHandler::class,
            ApplyCouponToBasket::class => ApplyCouponToBasketHandler::class,
            CreateBasket::class => CreateBasketHandler::class,
        ];
    }

    public function handle(CommandInterface $command): void
    {
        $commandClass = get_class($command);

        if ($this->serviceLocator->has($commandClass)) {
            $handler = $this->serviceLocator->get($commandClass);
        } else {
            throw new \InvalidArgumentException();
        }

        if (! $handler instanceof CommandHandlerInterface) {
            throw new \InvalidArgumentException();
        }

        $handler($command);
    }
}
