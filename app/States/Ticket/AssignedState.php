<?php

namespace App\States\Ticket;

use App\Models\Ticket;
use App\Exceptions\InvalidTicketTransitionException;

class AssignedState implements TicketState
{
    protected Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function assign(): void
    {
        // Re-assignment within the assigned state is allowed
        $this->ticket->save();
    }

    public function start(): void
    {
        $this->ticket->status = 'in_progress';
        $this->ticket->save();
    }

    public function resolve(): void
    {
        throw new InvalidTicketTransitionException('assigned', 'resolved');
    }

    public function close(): void
    {
        throw new InvalidTicketTransitionException('assigned', 'closed');
    }

    public function reopen(): void
    {
        throw new InvalidTicketTransitionException('assigned', 'reopened');
    }
}
