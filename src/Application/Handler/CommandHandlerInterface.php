<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CommandInterface;

interface CommandHandlerInterface
{
    public function __invoke(CommandInterface $command): void;
}
