<?php

namespace App\States\Ticket;

use App\Models\Ticket;
use App\Exceptions\InvalidTicketTransitionException;

class ClosedState implements TicketState
{
    protected Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function assign(): void
    {
        throw new InvalidTicketTransitionException('closed', 'assigned');
    }

    public function start(): void
    {
        throw new InvalidTicketTransitionException('closed', 'in_progress');
    }

    public function resolve(): void
    {
        throw new InvalidTicketTransitionException('closed', 'resolved');
    }

    public function close(): void
    {
        throw new InvalidTicketTransitionException('closed', 'closed');
    }

    public function reopen(): void
    {
        $this->ticket->status = 'reopened';
        $this->ticket->save();
    }
}
