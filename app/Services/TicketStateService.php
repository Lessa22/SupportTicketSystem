<?php

namespace App\Services;

use App\Exceptions\InvalidTicketTransitionException;
use App\Models\Ticket;

class TicketStateService
{
    private array $transitions = [

        'open' => [
            'assigned'
        ],

        'assigned' => [
            'in_progress'
        ],

        'in_progress' => [
            'resolved'
        ],

        'resolved' => [
            'closed'
        ],

        'closed' => [
            'reopened'
        ],

        'reopened' => [
            'assigned'
        ],
    ];

    public function changeStatus(Ticket $ticket, string $newStatus): void
    {
        $current = $ticket->status;

        if (
            ! isset($this->transitions[$current]) ||
            ! in_array($newStatus, $this->transitions[$current])
        ) {
            throw new InvalidTicketTransitionException(
                $current,
                $newStatus
            );
        }

        $ticket->status = $newStatus;

        $ticket->save();
    }
}