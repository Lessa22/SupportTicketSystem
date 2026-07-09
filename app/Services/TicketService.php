<?php

namespace App\Services;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;

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
        return $this->repository->create($data);
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