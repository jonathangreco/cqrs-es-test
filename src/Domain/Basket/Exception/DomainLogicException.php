<?php

declare(strict_types=1);

namespace App\Domain\Basket\Exception;

class DomainLogicException extends \DomainException
{
    public static function invalidBasketPrice(string $string)
    {
        return new self($string);
    }
}
