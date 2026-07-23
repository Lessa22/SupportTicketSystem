<?php

namespace App\States\Ticket;

use App\Models\Ticket;
use App\Exceptions\InvalidTicketTransitionException;

class ResolvedState implements TicketState
{
    protected Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function assign(): void
    {
        throw new InvalidTicketTransitionException('resolved', 'assigned');
    }

    public function start(): void
    {
        throw new InvalidTicketTransitionException('resolved', 'in_progress');
    }

    public function resolve(): void
    {
        throw new InvalidTicketTransitionException('resolved', 'resolved');
    }

    public function close(): void
    {
        $this->ticket->status = 'closed';
        $this->ticket->save();
    }

    public function reopen(): void
    {
        throw new InvalidTicketTransitionException('resolved', 'reopened');
    }
}
