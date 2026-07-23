<?php

namespace App\States\Ticket;

use App\Models\Ticket;
use App\Exceptions\InvalidTicketTransitionException;

class OpenState implements TicketState
{
    protected Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function assign(): void
    {
        $this->ticket->status = 'assigned';
        $this->ticket->save();
    }

    public function start(): void
    {
        throw new InvalidTicketTransitionException('open', 'in_progress');
    }

    public function resolve(): void
    {
        throw new InvalidTicketTransitionException('open', 'resolved');
    }

    public function close(): void
    {
        throw new InvalidTicketTransitionException('open', 'closed');
    }

    public function reopen(): void
    {
        throw new InvalidTicketTransitionException('open', 'reopened');
    }
}
