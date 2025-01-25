<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Application\Command\CreateBasket;
use App\Infrastructure\Persistence\RedisEventStore;
use App\Infrastructure\Service\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:basket:create', description: 'Given a basket ID with its price')]
class CreateBasketCommand extends Command
{
    public function __construct(private CommandBusInterface $commandBus, private RedisEventStore $redisEventStore)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Ajouter un panier')
            ->addArgument('basket', InputArgument::REQUIRED, 'ID du panier')
            ->addArgument('price', InputArgument::REQUIRED, 'prix du panier');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->redisEventStore->changePrefix('basket:');
        $basketId = (int) $input->getArgument('basket');

        if ($this->redisEventStore->exists($basketId)) {
            $output->writeln('!!!SAVAGE REJECTION !!! le panier existe déjà');

            return Command::FAILURE;
        }

        $this->commandBus->handle(new CreateBasket($basketId, (float) $input->getArgument('price')));

        dump($this->redisEventStore->getEvents($basketId));

        return Command::SUCCESS;
    }
}
