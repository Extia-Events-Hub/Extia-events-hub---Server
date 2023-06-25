@component('mail::message')
{{$userName}} has received a ticket

Some details about the ticket...

@component('mail::button', ['url' => 'http://127.0.0.1:8000/'])
More details    
@endcomponent

Thanks <br>
Extia Events Group
    
@endcomponent