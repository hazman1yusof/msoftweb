@component('mail::message')
# Hello, {{$user->username}}

There is an update on your ticket

<a href="{{ config('app.url') }}/ticket/{{$ticket_id}}">Go to Ticket id {{$ticket_id}}</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
