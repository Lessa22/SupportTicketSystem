<?php

namespace App\Strategies\Assignment;

use App\Models\Ticket;
use App\Models\User;

class RoundRobinAssignmentStrategy implements AssignmentStrategy
{
    public function assign(Ticket $ticket): void
    {
        $agent = User::where('role','agent')
            ->orderBy('id')
            ->first();

        if($agent){

            $ticket->agent_id = $agent->id;

            $ticket->save();

        }
    }
}