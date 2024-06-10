@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    Dear {{ $data['username'] }},
    Welcome to Drivrr! Your account has been created successfully by the Admin as a Busniess Owner.
    <div>
        Here are your account details:
        <ul style="padding-left: 16px">
            <li><strong>Email:</strong> {{ $data['useremail'] }}</li>
            <li><strong>Password:</strong> {{ $data['password'] }}</li>
        </ul>
    </div>
    <div>
        <div style="padding-top: 10px">
            Thanks,<br>
            Drivrr
        </div>
    </div>
@endcomponent
