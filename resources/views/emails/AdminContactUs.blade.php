@component('mail::message')
    {{-- Header --}}
    # Contact Us Form Submission

    {{-- Content --}}
    **Email:** {{ $email }}
    **Description:**
    {{ $description }}
    {{ config('app.name') }}
@endcomponent
