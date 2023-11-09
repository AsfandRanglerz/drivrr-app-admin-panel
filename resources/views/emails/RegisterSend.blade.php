@component('mail::message')

# Congrulation you have signed up successfully.

 

@if ($status === 'Owner')
    You are successfully registered as {{$status}}.
@else
    You are successfully registered as {{$status}}.
@endif

Thanks, <br>
{{ config('app.name') }}

@endcomponent
