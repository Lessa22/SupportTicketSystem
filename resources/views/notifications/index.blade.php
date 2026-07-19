<x-app-layout>

<div class="container">

<h2>Notifications</h2>

@foreach($notifications as $notification)

<div class="alert alert-info">

{{ $notification->message }}

</div>

@endforeach

</div>

</x-app-layout>