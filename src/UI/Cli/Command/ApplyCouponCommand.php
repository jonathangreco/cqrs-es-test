<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Application\Command\ApplyCouponToBasket;
use App\Infrastructure\Persistence\RedisEventStore;
use App\Infrastructure\Service\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(name: 'app:coupon:apply', description: 'Given a coupon ID apply the discount')]
class ApplyCouponCommand extends Command
{
    public function __construct(private CommandBusInterface $commandBus, private RedisEventStore $redisEventStore)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setDescription('Appliquer un coupon')
            ->addArgument('coupon', InputArgument::REQUIRED, 'ID du coupon')
            ->addArgument('basket', InputArgument::REQUIRED, 'ID du panier');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->redisEventStore->changePrefix('coupon:');
        $couponId = (int) $input->getArgument('coupon');
        $couponExist = $this->redisEventStore->exists($couponId);

        $this->redisEventStore->changePrefix('basket:');
        $basketId = (int) $input->getArgument('basket');
        $basketExist = $this->redisEventStore->exists($basketId);


        if (! $couponExist || ! $basketExist) {
            $output->writeln("!!!SAVAGE REJECTION !!! le coupon/basket n'existe pas");

            return Command::FAILURE;
        }

        $this->redisEventStore->changePrefix('basket:');
        dump($this->redisEventStore->getEvents($basketId));

        $this->commandBus->handle(new ApplyCouponToBasket($couponId, $basketId));


        return Command::SUCCESS;
    }

}
