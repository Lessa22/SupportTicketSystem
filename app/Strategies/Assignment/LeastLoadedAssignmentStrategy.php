<?php

namespace App\Strategies\Assignment;

use App\Models\Ticket;
use App\Models\User;

class LeastLoadedAssignmentStrategy implements AssignmentStrategy
{
    public function assign(Ticket $ticket): void
    {
        $agent = User::where('role', 'agent')
            ->withCount(['assignedTickets' => function ($query) {
                $query->whereNotIn('status', ['resolved', 'closed']);
            }])
            ->orderBy('assigned_tickets_count', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        if ($agent) {
            $ticket->agent_id = $agent->id;
            $ticket->status = 'assigned';
        } else {
            $ticket->agent_id = null;
            $ticket->status = 'open';
        }

        $ticket->save();
    }
}