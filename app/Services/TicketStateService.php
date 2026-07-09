<?php

namespace App\Services;

use App\Enums\TicketStatus;
use App\Exceptions\InvalidTicketTransitionException;
use App\Models\Ticket;

class TicketStateService
{
    public function changeStatus(Ticket $ticket, TicketStatus $newStatus): void
    {
        $allowedTransitions = [
            TicketStatus::OPEN->value => [
                TicketStatus::ASSIGNED->value
            ],

            TicketStatus::ASSIGNED->value => [
                TicketStatus::IN_PROGRESS->value
            ],

            TicketStatus::IN_PROGRESS->value => [
                TicketStatus::RESOLVED->value
            ],

            TicketStatus::RESOLVED->value => [
                TicketStatus::CLOSED->value
            ],

            TicketStatus::CLOSED->value => [
                TicketStatus::REOPENED->value
            ],

            TicketStatus::REOPENED->value => [
                TicketStatus::ASSIGNED->value
            ],
        ];

        if (! in_array(
            $newStatus->value,
            $allowedTransitions[$ticket->status] ?? []
        )) {
            throw new InvalidTicketTransitionException(
                "Invalid transition from {$ticket->status} to {$newStatus->value}"
            );
        }

        $ticket->status = $newStatus->value;

        $ticket->save();
    }
}