<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Infrastructure\Persistence\RedisEventStore;
use App\Infrastructure\Service\CommandBusInterface;
use App\Application\Command\CreateCoupon;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:coupon:create', description: 'Given a coupon ID and a discount amount')]
class CreateCouponCommand extends Command
{
    public function __construct(private CommandBusInterface $commandBus, private RedisEventStore $redisEventStore)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Ajouter un coupon')
            ->addArgument('coupon', InputArgument::REQUIRED, 'ID du coupon')
            ->addArgument('discount', InputArgument::REQUIRED, 'montant de la reduction');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->redisEventStore->changePrefix('coupon:');
        $couponId = (int) $input->getArgument('coupon');

        if ($this->redisEventStore->exists($couponId)) {
            $output->writeln('!!!SAVAGE REJECTION !!! le coupon existe déjà');

            return Command::FAILURE;
        }

        $this->commandBus->handle(new CreateCoupon($couponId, (float) $input->getArgument('discount')));

        dump($this->redisEventStore->getEvents($couponId));

        return Command::SUCCESS;
    }
}
