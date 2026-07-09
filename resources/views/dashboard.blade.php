<x-app-layout>

    <div class="p-8">

        <h1 class="text-3xl font-bold">

            Dashboard

        </h1>

        <div class="grid grid-cols-3 gap-5 mt-8">

            <div class="bg-white rounded shadow p-5">

                <h2>Total Tickets</h2>

                <p class="text-4xl">{{ $totalTickets }}</p>

            </div>

            <div class="bg-white rounded shadow p-5">

                <h2>Open</h2>

                <p class="text-4xl">{{ $openTickets }}</p>

            </div>

            <div class="bg-white rounded shadow p-5">

                <h2>Closed</h2>

                <p class="text-4xl">{{ $closedTickets }}</p>

            </div>

        </div>

    </div>

</x-app-layout>