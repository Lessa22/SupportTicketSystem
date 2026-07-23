<?php

namespace App\Exceptions;

class InvalidTicketTransitionException extends DomainException
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(
            "Cannot change ticket status from '{$from}' to '{$to}'."
        );
    }
}