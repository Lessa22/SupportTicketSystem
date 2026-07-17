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

        </div>

    </div>
</div>

</x-app-layout>