<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Command\CommandInterface;

interface CommandBusInterface
{
    public function handle(CommandInterface $command);
}
