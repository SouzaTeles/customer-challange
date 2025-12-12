<?php

namespace App\Exceptions;

use DomainException;

class CpfAlreadyExistsException extends DomainException {
    protected $code = 409;
	protected $message = "Esse CPF já foi cadastrado no sistema.";

}