<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class NotAllowedException extends RuntimeException
{
	protected $code = 405;
    protected $message = 'Method Not Allowed';
}