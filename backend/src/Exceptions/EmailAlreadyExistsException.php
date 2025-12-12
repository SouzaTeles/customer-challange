<?php

namespace App\Exceptions;

use DomainException;

class EmailAlreadyExistsException extends DomainException
{
    protected $code = 409;
	protected $message = "Esse e-mail já foi cadastrado no sistema.";

}