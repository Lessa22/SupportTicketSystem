<?php

namespace App\Strategies\Assignment;

use App\Models\Ticket;
use App\Models\User;

class RoundRobinAssignmentStrategy implements AssignmentStrategy
{
    public function assign(Ticket $ticket): void
    {
        $agents = User::where('role', 'agent')->orderBy('id')->get();
        if ($agents->isEmpty()) {
            $ticket->agent_id = null;
            $ticket->status = 'open';
            $ticket->save();
            return;
        }

        // Find the last ticket that was assigned to one of these agents
        $lastAssignedTicket = Ticket::whereNotNull('agent_id')
            ->whereIn('agent_id', $agents->pluck('id'))
            ->orderBy('id', 'desc')
            ->first();

        $nextAgent = null;
        if ($lastAssignedTicket) {
            $lastAgentId = $lastAssignedTicket->agent_id;
            $currentIndex = $agents->search(fn($agent) => $agent->id == $lastAgentId);
            if ($currentIndex !== false) {
                $nextIndex = ($currentIndex + 1) % $agents->count();
                $nextAgent = $agents[$nextIndex];
            }
        }

        if (!$nextAgent) {
            $nextAgent = $agents->first();
        }

        $ticket->agent_id = $nextAgent->id;
        $ticket->status = 'assigned';
        $ticket->save();
    }
}