@component('mail::message')
    # Welcome to {{ config('app.name') }}

    @if ($id == 3)
        Thank you for joining as a driver!
        <p>We're excited to have you on board. You can now start exploring and using our platform for drivers.</p>
    @elseif ($id == 2)
        Congratulations! You've successfully registered as a business owner.
        <p>Your journey with {{ config('app.name') }} as a business owner begins now.</p>
    @else
        Welcome, Sub Admin!
        <p>You've been successfully registered as an SubAdmin. Get ready to manage and oversee the platform.</p>
    @endif

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
