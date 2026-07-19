<?php

namespace App\Services;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
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
$data['sla_deadline'] = now()->addHours(24);

$ticket = $this->repository->create($data);
    app(AssignmentService::class)
        ->assign($ticket, 'round_robin');

    return $ticket;
}

    public function update(Ticket $ticket, array $data): bool
    {
        return $this->repository->update($ticket, $data);
    }

    public function delete(Ticket $ticket): bool
    {
        return $this->repository->delete($ticket);
    }
}