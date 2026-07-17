<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [

            'totalTickets' => Ticket::count(),

            'openTickets' => Ticket::where('status','open')->count(),

            'assignedTickets' => Ticket::where('status','assigned')->count(),

            'progressTickets' => Ticket::where('status','in_progress')->count(),

            'resolvedTickets' => Ticket::where('status','resolved')->count(),

            'closedTickets' => Ticket::where('status','closed')->count(),

            'categories'=>Category::withCount('tickets')->get(),

            'priorities'=>Priority::withCount('tickets')->get(),

        ]);
    }
}