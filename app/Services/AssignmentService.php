<?php

namespace App\Services;

use App\Models\Ticket;
use App\Strategies\Assignment\AssignmentManager;
use App\Strategies\Assignment\ManualAssignmentStrategy;
use App\Strategies\Assignment\RoundRobinAssignmentStrategy;
use App\Strategies\Assignment\LeastLoadedAssignmentStrategy;

class AssignmentService
{
    public function assign(Ticket $ticket, string $strategy = 'manual'): void
    {
        $manager = match ($strategy) {

            'round_robin' => new AssignmentManager(
                new RoundRobinAssignmentStrategy()
            ),

            'least_loaded' => new AssignmentManager(
                new LeastLoadedAssignmentStrategy()
            ),

            default => new AssignmentManager(
                new ManualAssignmentStrategy()
            ),
        };

        $manager->assign($ticket);
    }
}