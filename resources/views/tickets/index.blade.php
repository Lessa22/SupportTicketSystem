<x-app-layout>

<div class="max-w-7xl mx-auto py-8">

<div class="flex justify-between items-center mb-8">



<form method="GET" class="mb-6">

<div class="grid grid-cols-4 gap-4">

<input
type="text"
name="search"
placeholder="Search ticket..."
value="{{ request('search') }}"
class="border rounded p-2">

<select
name="status"
class="border rounded p-2">

<option value="">All Status</option>

<option value="open">Open</option>

<option value="assigned">Assigned</option>

<option value="in_progress">In Progress</option>

<option value="resolved">Resolved</option>

<option value="closed">Closed</option>

</select>

<select
name="priority"
class="border rounded p-2">

<option value="">All Priorities</option>

@foreach($priorities as $priority)

<option
value="{{ $priority->id }}"
@if(request('priority')==$priority->id) selected @endif>

{{ $priority->name }}

</option>

@endforeach

</select>

<select
name="category"
class="border rounded p-2">

<option value="">All Categories</option>

@foreach($categories as $category)

<option
value="{{ $category->id }}"
@if(request('category')==$category->id) selected @endif>

{{ $category->name }}

</option>

@endforeach

</select>

</div>

<div class="mt-4">

<button
class="bg-blue-600 text-white px-5 py-2 rounded">

Search

</button>

<a
href="{{ route('tickets.index') }}"
class="bg-gray-500 text-white px-5 py-2 rounded">

Reset

</a>

</div>

</form>



<h1 class="text-3xl font-bold">

Tickets

</h1>

<a
href="{{ route('tickets.create') }}"
class="bg-blue-600 text-white px-5 py-3 rounded">

New Ticket

</a>

</div>

@if(session('success'))

<div class="bg-green-200 p-4 rounded mb-5">

{{ session('success') }}

</div>

@endif



<table class="w-full border">

<thead>

<tr>

<th class="p-3">Title</th>

<th>Status</th>

<th>Category</th>

<th>Priority</th>

<th>Actions</th>

</tr>

</thead>

<tbody>

@foreach($tickets as $ticket)

<tr class="border-t">

<td class="p-3">

{{ $ticket->title }}

</td>

<td>

@if($ticket->status == 'open')
    <span class="px-2 py-1 rounded bg-green-100 text-green-700">Open</span>

@elseif($ticket->status == 'assigned')
    <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">Assigned</span>

@elseif($ticket->status == 'in_progress')
    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">In Progress</span>

@elseif($ticket->status == 'resolved')
    <span class="px-2 py-1 rounded bg-emerald-100 text-emerald-700">Resolved</span>

@else
    <span class="px-2 py-1 rounded bg-gray-200 text-gray-700">Closed</span>
@endif

</td>

<td>

{{ $ticket->category->name }}

</td>

<td>

{{ $ticket->priority->name }}

</td>

<td>

<a
href="{{ route('tickets.show',$ticket) }}"
class="text-blue-600">

View

</a>

|

<a
href="{{ route('tickets.edit',$ticket) }}"
class="text-yellow-600">

Edit

</a>

|

<form
action="{{ route('tickets.destroy',$ticket) }}"
method="POST"
class="inline">

@csrf

@method('DELETE')

<button
onclick="return confirm('Delete this ticket ?')"
class="text-red-600">

Delete

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-8">

{{ $tickets->links() }}

</div>

</div>

</x-app-layout>