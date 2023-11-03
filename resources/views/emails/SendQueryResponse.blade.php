@component('mail::message')
    # Response

    {{ $message }}
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
