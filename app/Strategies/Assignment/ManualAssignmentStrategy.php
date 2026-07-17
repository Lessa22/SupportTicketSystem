<?php

namespace App\Strategies\Assignment;

use App\Models\User;
use App\Models\Ticket;

class ManualAssignmentStrategy implements AssignmentStrategy
{
    public function assign(Ticket $ticket): void
    {
        $agent = User::where('role', 'agent')->first();

        if ($agent) {
            $ticket->agent_id = $agent->id;
            $ticket->save();
        }
    }
}