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
            'user_id' => auth()->id() ?? $ticket->customer_id,
            'action' => 'created',
            'description' => 'Ticket created.',
        ]);

        Notification::create([
            'user_id' => $ticket->customer_id,
            'ticket_id' => $ticket->id,
            'message' => 'Your ticket has been created successfully.',
        ]);

        if ($ticket->agent_id) {
            Notification::create([
                'user_id' => $ticket->agent_id,
                'ticket_id' => $ticket->id,
                'message' => 'A new ticket has been assigned to you: ' . $ticket->title,
            ]);
        }
    }

    public function updated(Ticket $ticket): void
    {
        if ($ticket->wasChanged('status')) {
            $from = $ticket->getOriginal('status');
            $to = $ticket->status;

            ActivityLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'status_changed',
                'description' => "Status changed from '{$from}' to '{$to}'.",
            ]);

            Notification::create([
                'user_id' => $ticket->customer_id,
                'ticket_id' => $ticket->id,
                'message' => "Ticket status changed to " . ucfirst(str_replace('_', ' ', $to)) . ".",
            ]);

            if ($ticket->agent_id && $ticket->agent_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $ticket->agent_id,
                    'ticket_id' => $ticket->id,
                    'message' => "Ticket status changed to " . ucfirst(str_replace('_', ' ', $to)) . ".",
                ]);
            }
        }

        if ($ticket->wasChanged('agent_id')) {
            $agentName = $ticket->agent ? $ticket->agent->name : 'None';

            ActivityLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'agent_assigned',
                'description' => "Ticket assigned to agent: {$agentName}.",
            ]);

            if ($ticket->agent_id) {
                Notification::create([
                    'user_id' => $ticket->agent_id,
                    'ticket_id' => $ticket->id,
                    'message' => "You have been assigned to ticket #" . $ticket->id . ".",
                ]);
            }
        }
    }
}