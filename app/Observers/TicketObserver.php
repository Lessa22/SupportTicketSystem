<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\Notification;

class TicketObserver
{
    public function created(Ticket $ticket): void
    {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'description' => 'Ticket created.',
        ]);
        Notification::create([
    'user_id' => $ticket->customer_id,
    'ticket_id' => $ticket->id,
    'message' => 'Your ticket has been created.',
]);
    }

    public function updated(Ticket $ticket): void
    {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'updated',
            'description' => 'Ticket updated.',
        ]);
        Notification::create([
    'user_id' => $ticket->customer_id,
    'ticket_id' => $ticket->id,
    'message' => 'Ticket status changed to '.$ticket->status,
]);
    }

    public function deleted(Ticket $ticket): void
    {
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'description' => 'Ticket deleted.',
        ]);
    }
}