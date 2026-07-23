<?php

namespace App\Services;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Models\Setting;
use App\Strategies\Sla\SlaStrategyFactory;
use App\Services\AssignmentService;

class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $repository
    ) {
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById(int $id): ?Ticket
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Ticket
    {
        $data['customer_id'] = auth()->id();
        $data['status'] = 'open';

        // 1. Create the ticket record first
        $ticket = $this->repository->create($data);

        // 2. Calculate SLA deadline via Strategy Factory
        $slaStrategy = SlaStrategyFactory::make($ticket);
        $ticket->sla_deadline = $slaStrategy->calculateDeadline($ticket);
        $ticket->save();

        // 3. Assign ticket via dynamic Assignment Strategy
        $strategy = Setting::get('active_assignment_strategy', 'round_robin');
        app(AssignmentService::class)->assign($ticket, $strategy);

        return $ticket;
    }

    public function update(Ticket $ticket, array $data): bool
    {
        $priorityChanged = isset($data['priority_id']) && (int)$data['priority_id'] !== (int)$ticket->priority_id;
        $agentChanged = isset($data['agent_id']) && (int)$data['agent_id'] !== (int)$ticket->agent_id;

        $success = $this->repository->update($ticket, $data);

        if ($success) {
            if ($priorityChanged) {
                // Re-calculate SLA deadline if priority changes
                $slaStrategy = SlaStrategyFactory::make($ticket);
                $ticket->sla_deadline = $slaStrategy->calculateDeadline($ticket);
                $ticket->save();
            }

            if ($agentChanged && $ticket->agent_id) {
                if ($ticket->status === 'open' || $ticket->status === 'reopened') {
                    $ticket->getState()->assign();
                }
            }
        }

        return $success;
    }

    public function delete(Ticket $ticket): bool
    {
        return $this->repository->delete($ticket);
    }
}