@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    We have received reset password request, please click below button to reset password.
    @component('mail::button', ['url' => $detail['url']])
        Reset Password
    @endcomponent

    Thanks
    {{ config('app.name') }}
@endcomponent
