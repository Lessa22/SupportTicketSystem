<?php

namespace App\Services;

use App\Exceptions\InvalidTicketTransitionException;
use App\Models\Ticket;

class TicketStateService
{
    public function changeStatus(Ticket $ticket, string $newStatus): void
    {
        $state = $ticket->getState();

        match ($newStatus) {
            'assigned' => $state->assign(),
            'in_progress' => $state->start(),
            'resolved' => $state->resolve(),
            'closed' => $state->close(),
            'reopened' => $state->reopen(),
            default => throw new InvalidTicketTransitionException($ticket->status, $newStatus),
        };
    }
}