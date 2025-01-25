<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Infrastructure\Persistence\RedisEventStore;
use App\Infrastructure\Service\CommandBusInterface;
use App\Application\Command\RevokeCoupon;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:coupon:revoke', description: 'Revoke a coupon')]
class RevokeCouponCommand extends Command
{
    public function __construct(private CommandBusInterface $commandBus, private RedisEventStore $redisEventStore)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Revoque un coupon')
            ->addArgument('coupon', InputArgument::REQUIRED, 'ID du coupon');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->redisEventStore->changePrefix('coupon:');
        $couponId = (int) $input->getArgument('coupon');

        if (! $this->redisEventStore->exists($couponId)) {
            $output->writeln('!!!SAVAGE REJECTION !!! le coupon existe pas');

            return Command::FAILURE;
        }

        $this->commandBus->handle(new RevokeCoupon($couponId));

        dump($this->redisEventStore->getEvents($couponId));

        return Command::SUCCESS;
    }
}
