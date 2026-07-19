<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();

        $openTickets = Ticket::where('status', 'open')->count();

        $assignedTickets = Ticket::where('status', 'assigned')->count();

        $progressTickets = Ticket::where('status', 'in_progress')->count();

        $resolvedTickets = Ticket::where('status', 'resolved')->count();

        $closedTickets = Ticket::where('status', 'closed')->count();

        $recentTickets = Ticket::latest()->take(5)->get();

                $highPriority = Ticket::where('priority_id',3)->count();

$todayTickets = Ticket::whereDate(
    'created_at',
    today()
)->count();
        return view('dashboard', compact(
            'totalTickets',
            'openTickets',
            'assignedTickets',
            'progressTickets',
            'resolvedTickets',
            'closedTickets',
            'recentTickets',
            'highPriority',
            'todayTickets'
        ));

    }
    
}