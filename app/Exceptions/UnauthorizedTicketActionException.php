<?php

namespace App\Exceptions;

class UnauthorizedTicketActionException extends DomainException
{
    public function __construct(string $action = "This action is unauthorized.")
    {
        parent::__construct($action);
    }
}
