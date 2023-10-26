@component('mail::message')
    # Response

    <h1>{{ $message }}</h1>
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
