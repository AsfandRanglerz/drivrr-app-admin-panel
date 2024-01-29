<div style="color: #fff; padding: 20px; border-radius: 10px;background-color:#EA7527;">
    <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Welcome to {{ config('app.name') }}</h2>

    @if ($id == 3)
        <p style="font-size: 16px;">Thank you for joining as a driver!</p>
        <p style="font-size: 16px;">We're excited to have you on board. You can now start exploring and using our
            platform for drivers.</p>
    @elseif ($id == 2)
        <p style="font-size: 16px;">Congratulations! You've successfully registered as a business owner.</p>
        <p style="font-size: 16px;">Your journey with {{ config('app.name') }} as a business owner begins now.</p>
    @else
        <p style="font-size: 16px;">Welcome, Sub Admin!</p>
        <p style="font-size: 16px;">You've been successfully registered as a SubAdmin. Get ready to manage and oversee
            the platform.</p>
    @endif

    <p style="font-size: 16px; margin-top: 20px;color:#fff">Thanks,<br>{{ config('app.name') }}</p>
</div>
