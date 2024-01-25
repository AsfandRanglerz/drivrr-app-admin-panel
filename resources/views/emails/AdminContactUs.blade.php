@component('mail::message')
    <p>Email: {{ $email }}</p>
    <p>Description:</p>
    <p>{{ $description }}</p>


    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
