<?php

namespace App\States\Ticket;

use App\Models\Ticket;
use App\Exceptions\InvalidTicketTransitionException;

class InProgressState implements TicketState
{
    protected Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function assign(): void
    {
        throw new InvalidTicketTransitionException('in_progress', 'assigned');
    }

    public function start(): void
    {
        throw new InvalidTicketTransitionException('in_progress', 'in_progress');
    }

    public function resolve(): void
    {
        $this->ticket->status = 'resolved';
        $this->ticket->save();
    }

    public function close(): void
    {
        throw new InvalidTicketTransitionException('in_progress', 'closed');
    }

    public function reopen(): void
    {
        throw new InvalidTicketTransitionException('in_progress', 'reopened');
    }
}
