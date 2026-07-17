<?php

namespace App\Exceptions;

use Exception;

class InvalidTicketTransitionException extends Exception
{
    public function __construct(string $from, string $to)
    {
        parent::__construct(
            "Cannot change ticket status from '{$from}' to '{$to}'."
        );
    }
}