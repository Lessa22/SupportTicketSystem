<?php

namespace App\Strategies\Assignment;

use App\Models\User;
use App\Models\Ticket;

class LeastLoadedAssignmentStrategy implements AssignmentStrategy
{
    public function assign(Ticket $ticket): void
    {
        $agent = User::where('role','agent')
            ->withCount('assignedTickets')
            ->orderBy('assigned_tickets_count')
            ->first();

        if($agent){

            $ticket->agent_id = $agent->id;

            $ticket->save();

        }
    }
}