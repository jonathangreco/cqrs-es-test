<?php

namespace App\UI\Cli\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test-redis',
    description: 'Create link contents to group and to user, to be launched after migration is completed'
)]
class TestRedisCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $redis = new \Redis();
        $redis->connect('redis-danim');
        $redis->set('test-danim', 'yop');
        $value = $redis->get('test-danim');
        $output->writeln('Value from Redis: ' . $value);

        $redis->flushAll();

        $output->writeln('Toutes les données ont été supprimées: ' . $value);

        return Command::SUCCESS;
    }
}
