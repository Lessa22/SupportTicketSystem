<?php

namespace App\States\Ticket;

use App\Models\Ticket;
use App\Exceptions\InvalidTicketTransitionException;

class ReopenedState implements TicketState
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
        throw new InvalidTicketTransitionException('reopened', 'in_progress');
    }

    public function resolve(): void
    {
        throw new InvalidTicketTransitionException('reopened', 'resolved');
    }

    public function close(): void
    {
        throw new InvalidTicketTransitionException('reopened', 'closed');
    }

    public function reopen(): void
    {
        throw new InvalidTicketTransitionException('reopened', 'reopened');
    }
}
