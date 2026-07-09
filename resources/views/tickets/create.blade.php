<x-app-layout>

<div class="max-w-4xl mx-auto py-8">

<h1 class="text-3xl font-bold mb-8">

Create Ticket

</h1>

<form
method="POST"
action="{{ route('tickets.store') }}">

@include('tickets.form')

</form>

</div>

</x-app-layout>