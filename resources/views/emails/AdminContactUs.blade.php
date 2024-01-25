@component('mail::message')
    # Contact Us Form Submission

    **Email:** {{ $email }}

    **Description:**
    {{ $description }}

    Thanks,
    {{ config('app.name') }}
@endcomponent
