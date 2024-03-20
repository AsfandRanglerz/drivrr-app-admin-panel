@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    <div style="align-items: center">
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Congratulations!</h2>
        <p style="font-size: 16px;">You have successfully signed up with {{ config('app.name') }}.</p>
        @if ($status === 'Owner')
            <p style="font-size: 16px;">You are now registered as a {{ $status }}.</p>
        @else
            <p style="font-size: 16px;">You are now registered as a {{ $status }}.</p>
        @endif
        <p style="font-size: 16px; margin-top: 20px;">Thanks for joining us!</p>
        <p style="font-size: 14px;">Drivrr</p>
    </div>
@endcomponent
