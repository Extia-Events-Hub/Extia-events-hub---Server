@component('mail::message')
{{ $userName }} has received a ticket

Some details about the ticket: <br>
Title: {{ $title['en'] }} <br>
Modaliy: {{ $mode['en']['isPresential'] ? 'Presential' : 'Online' }} <br>
Location: {{ $mode['en']['location'] }} <br>
Date: {{ $date }} <br>
Time: {{ $time }} <br>

@component('mail::button', ['url' => 'http://127.0.0.1:8000/'])
More details
@endcomponent

Thanks, <br>
Extia Events Group
@endcomponent


{{-- Some details about the ticket: <br> <br>
Title: {{ $title['en'] ?? '' }} <br> <br>
Mode: {{ $mode['es']['isPresential'] ?? '' ? 'Presential' : 'Online' }} <br> <br>
Location: {{ $mode['es']['location'] ?? '' }} <br> <br> --}}
