<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class NotAllowedException extends RuntimeException
{
    public function __construct(string $message = 'Metodo não permitido')
    {
        parent::__construct($message, 405);
    }
}