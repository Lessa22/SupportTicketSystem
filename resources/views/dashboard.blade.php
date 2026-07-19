<x-app-layout>

<div class="py-8">
    <div class="max-w-7xl mx-auto px-6">

        <h1 class="text-3xl font-bold text-gray-800 mb-8">
            📊 Support Ticket Dashboard
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-blue-500">
                <h2 class="text-gray-500">Total Tickets</h2>
                <p class="text-4xl font-bold text-blue-600">{{ $totalTickets }}</p>
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-green-500">
                <h2 class="text-gray-500">Open</h2>
                <p class="text-4xl font-bold text-green-600">{{ $openTickets }}</p>
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-yellow-500">
                <h2 class="text-gray-500">Assigned</h2>
                <p class="text-4xl font-bold text-yellow-600">{{ $assignedTickets }}</p>
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-indigo-500">
                <h2 class="text-gray-500">In Progress</h2>
                <p class="text-4xl font-bold text-indigo-600">{{ $progressTickets }}</p>
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-emerald-500">
                <h2 class="text-gray-500">Resolved</h2>
                <p class="text-4xl font-bold text-emerald-600">{{ $resolvedTickets }}</p>
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-red-500">
                <h2 class="text-gray-500">Closed</h2>
                <p class="text-4xl font-bold text-red-600">{{ $closedTickets }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-xl p-6 border-l-4 border-purple-500">
                <h2 class="text-gray-500">High Priority Tickets</h2>
                <p class="text-4xl font-bold text-purple-600">{{ $highPriority }}</p> 
            </div>

        </div>

    </div>
</div>

<div class="bg-white rounded-xl shadow mt-8 p-6">

    <h2 class="text-xl font-bold mb-4">
        Tickets by Status
    </h2>

    <canvas id="ticketChart"></canvas>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('ticketChart');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [
            'Open',
            'Assigned',
            'In Progress',
            'Resolved',
            'Closed'
        ],

        datasets: [{

            label: 'Tickets',

            data: [

                {{ $openTickets }},
                {{ $assignedTickets }},
                {{ $progressTickets }},
                {{ $resolvedTickets }},
                {{ $closedTickets }}

            ]

        }]

    }

});

</script>
<div class="bg-white rounded-xl shadow mt-8 p-6">

<h2 class="text-xl font-bold mb-4">
Recent Tickets
</h2>

<table class="w-full">

<thead>

<tr class="border-b">

<th class="text-left p-2">Title</th>

<th>Status</th>

<th>Priority</th>

</tr>

</thead>

<tbody>

@foreach($recentTickets as $ticket)

<tr class="border-b">

<td class="p-2">
    
<a
    href="{{ route('tickets.show',$ticket) }}"
    class="text-blue-600 hover:underline">

    {{ $ticket->title }}

</a>

</td>

<td>

{{ ucfirst($ticket->status) }}

</td>

<td>

{{ optional($ticket->priority)->name }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</x-app-layout>