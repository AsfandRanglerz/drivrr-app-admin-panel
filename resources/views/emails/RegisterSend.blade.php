@component('mail::message')
# Congrulation you have sign up sussessfully.

<p>This is your account password.</p>
<h3>{{$password}}</h3>

Thanks, <br>
{{ config('app.name') }}
@endcomponent
