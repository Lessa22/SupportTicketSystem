<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Http\Requests\StoreTicketRequest;

use App\Http\Requests\UpdateTicketRequest;

use App\Models\Category;
use App\Models\Priority;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Ticket::class);

        $tickets = $this->ticketService->getAll();
        $categories = Category::all();
        $priorities = Priority::all();

        return view('tickets.index', compact(
            'tickets',
            'categories',
            'priorities'
        ));
    }

    public function create()
    {
        Gate::authorize('create', Ticket::class);

        return view('tickets.create',[
            'categories'=>Category::all(),
            'priorities'=>Priority::all(),
        ]);
    }

    public function store(StoreTicketRequest $request)
    {
        Gate::authorize('create', Ticket::class);

        $this->ticketService->create(
            $request->validated()
        );

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);

        $ticket = Ticket::with([
            'category',
            'priority',
            'activityLogs.user',
            'messages.user'
        ])->findOrFail($ticket->id);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        return view('tickets.edit',[
            'ticket'=>$ticket,
            'categories'=>Category::all(),
            'priorities'=>Priority::all(),
            'agents'=>User::where('role', 'agent')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

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
        Gate::authorize('delete', $ticket);

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

    public function changeStatus(
        \Illuminate\Http\Request $request,
        \App\Models\Ticket $ticket,
        \App\Services\TicketStateService $stateService
    )
    {
        Gate::authorize('changeStatus', $ticket);

        $request->validate([
            'status' => 'required|string'
        ]);

        try {
            $stateService->changeStatus(
                $ticket,
                $request->status
            );

            return back()->with(
                'success',
                'Ticket status updated successfully.'
            );

        } catch (\Exception $e) {
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
