<?php

namespace App\Exceptions;

class AgentUnavailableException extends DomainException
{
    public function __construct(string $message = "No agents are available to handle the ticket.")
    {
        parent::__construct($message);
    }
}
