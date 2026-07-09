<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Http\Requests\StoreTicketRequest;

use App\Http\Requests\UpdateTicketRequest;

use App\Models\Category;

use App\Models\Priority;


class TicketController extends Controller
{
    public function index()
    {
        $tickets = $this->ticketService->getAll();

        return view('tickets.index', compact('tickets'));
    }

   public function create()
{
    return view('tickets.create',[
        'categories'=>Category::all(),
        'priorities'=>Priority::all(),
    ]);
}

  public function store(StoreTicketRequest $request)
{
    $this->ticketService->create($request->validated());

    return redirect()
        ->route('tickets.index')
        ->with('success','Ticket created successfully.');
}

    public function show(Ticket $ticket)
    {
       $ticket = $this->ticketService->getById($ticket->id);

return view('tickets.show', compact('ticket'));
    }

  public function edit(Ticket $ticket)
{
    return view('tickets.edit',[
        'ticket'=>$ticket,
        'categories'=>Category::all(),
        'priorities'=>Priority::all(),
    ]);
}

  public function update(UpdateTicketRequest $request,Ticket $ticket)
{
    $this->ticketService->update(
        $ticket,
        $request->validated()
    );

    return redirect()
        ->route('tickets.index')
        ->with('success','Ticket updated.');
}

  public function destroy(Ticket $ticket)
{
    $this->ticketService->delete($ticket);

    return redirect()
        ->route('tickets.index')
        ->with('success','Ticket deleted.');
}

    private TicketService $ticketService;

public function __construct(TicketService $ticketService)
{
    $this->ticketService = $ticketService;
}
}