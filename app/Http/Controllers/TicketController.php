<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;


class TicketController extends Controller
{
    public function index()
    {
        $tickets = $this->ticketService->getAll();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store()
    {

    }

    public function show(Ticket $ticket)
    {
       $ticket = $this->ticketService->getById($ticket->id);

return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Ticket $ticket)
    {

    }

    public function destroy(Ticket $ticket)
    {

    }

    private TicketService $ticketService;

public function __construct(TicketService $ticketService)
{
    $this->ticketService = $ticketService;
}
}