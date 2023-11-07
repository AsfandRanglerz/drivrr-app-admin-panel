@component('mail::message')

# Congrulation you have signed up successfully.

{{-- <p>This is your account password.</p>
<h3>{{$password}}</h3> --}}

@if ($status === 'Owner')
    You are successfully registered as {{$status}}.
@else
    You are successfully registered as {{$status}}.
@endif

Thanks, <br>
{{ config('app.name') }}

@endcomponent
