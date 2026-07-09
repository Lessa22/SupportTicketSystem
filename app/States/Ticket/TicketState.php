<?php

namespace App\States\Ticket;

interface TicketState
{
    public function assign(): void;

    public function start(): void;

    public function resolve(): void;

    public function close(): void;

    public function reopen(): void;
}