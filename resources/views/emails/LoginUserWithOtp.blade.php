@component('mail::message')
    # One-Time Password (OTP)

    Your One-Time Password (OTP) is:
    **{{ $OTP }}**

    Thanks
    From: {{ config('app.name') }}
@endcomponent
