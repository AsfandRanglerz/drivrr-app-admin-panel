@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    @if ($id == 3)
        <p style="font-size: 16px;">Thank you for joining as a Driver!</p>
        <p style="font-size: 16px;">We're excited to have you on board. You can now start exploring and using our
            platform for drivers.</p>
        <p style="font-size: 16px; margin-top: 20px;color:#fff">Thanks,<br>Drivrr</p>
    @elseif ($id == 2)
        <p style="font-size: 16px;">Congratulations! You've successfully registered as a Business owner.</p>
        <p style="font-size: 16px;">Your journey with Drivrr as a business owner begins now.</p>
        <p style="font-size: 16px; margin-top: 20px;color:#fff">Thanks,<br>Drivrr</p>
    @else
        <p style="font-size: 16px;">Welcome, Sub Admin!</p>
        <p style="font-size: 16px;">You've been successfully registered as a SubAdmin. Get ready to manage and oversee
            the platform.</p>
        <p style="font-size: 16px; margin-top: 20px;color:#fff">Thanks,<br>Drivrr</p>
    @endif
@endcomponent
