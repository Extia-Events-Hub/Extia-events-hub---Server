@component('mail::message')
<b>{{ $userName }} </b> <br>
 Thanks for join to the event!

 To following link will lead you to the event details.


{{-- Some details about the ticket: <br> <br>
Title: {{ $title['en'] ?? '' }} <br> <br>
Mode: {{ $mode['es']['isPresential'] ?? '' ? 'Presential' : 'Online' }} <br> <br>
Location: {{ $mode['es']['location'] ?? '' }} <br> <br>

Date: {{ $date }} <br>
Time: {{ $time }} <br> --}}

@component('mail::button', ['url' => 'https://extia-events-hub-client-nine.vercel.app/#/events/' . $id])
Event details
@endcomponent

Thanks, <br>
Extia Events Group
@endcomponent




