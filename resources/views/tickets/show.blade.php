<x-app-layout>

    <div class="max-w-5xl mx-auto py-8">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-8">

            <div class="flex justify-between items-center mb-6">

                <h1 class="text-3xl font-bold">
                    Ticket #{{ $ticket->id }}
                </h1>

                <a href="{{ route('tickets.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Back
                </a>

            </div>

            <hr class="mb-6">

            <div class="space-y-5">

                <div>
                    <h3 class="font-semibold text-gray-600">Title</h3>
                    <p class="text-lg">{{ $ticket->title }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-600">Description</h3>
                    <p>{{ $ticket->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <h3 class="font-semibold text-gray-600">Category</h3>
                        <p>{{ $ticket->category->name }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-600">Priority</h3>
                        <p>{{ $ticket->priority->name }}</p>
                    </div>

                </div>

                <div>

                    <h3 class="font-semibold text-gray-600 mb-2">Status</h3>

                    @php

                        $badge = match($ticket->status){

                            'open' => 'bg-blue-500',

                            'assigned' => 'bg-yellow-500',

                            'in_progress' => 'bg-indigo-500',

                            'resolved' => 'bg-green-600',

                            'closed' => 'bg-gray-700',

                            'reopened' => 'bg-purple-600',

                            default => 'bg-gray-500'
                        };

                    @endphp

                    <span class="{{ $badge }} text-white px-4 py-2 rounded-full">
                        {{ ucfirst(str_replace('_',' ', $ticket->status)) }}
                    </span>

                </div>

            </div>

            <hr class="my-8">

            <h2 class="text-xl font-bold mb-4">
                Change Status
            </h2>

            @if($ticket->status=='open')

                <form method="POST" action="{{ route('tickets.changeStatus',$ticket) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="assigned">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        Assign
                    </button>

                </form>

            @elseif($ticket->status=='assigned')

                <form method="POST" action="{{ route('tickets.changeStatus',$ticket) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="in_progress">

                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg">
                        Start Progress
                    </button>

                </form>

            @elseif($ticket->status=='in_progress')

                <form method="POST" action="{{ route('tickets.changeStatus',$ticket) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="resolved">

                    <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
                        Resolve
                    </button>

                </form>

            @elseif($ticket->status=='resolved')

                <form method="POST" action="{{ route('tickets.changeStatus',$ticket) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="closed">

                    <button class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-lg">
                        Close
                    </button>

                </form>

            @elseif($ticket->status=='closed')

                <form method="POST" action="{{ route('tickets.changeStatus',$ticket) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="reopened">

                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg">
                        Reopen
                    </button>

                </form>

            @elseif($ticket->status=='reopened')

                <form method="POST" action="{{ route('tickets.changeStatus',$ticket) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="assigned">

                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        Assign Again
                    </button>

                </form>

            @endif

        </div>

    </div>

</x-app-layout>