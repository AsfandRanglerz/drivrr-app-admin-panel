@component('mail::message')
    <div style="text-align:center;">
     <img src="{{ asset('public/admin/assets/img/blacklogo.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    Dear {{ $data['subadminname'] }},
    Welcome to Drivrr! Your account has been created successfully by the Admin.
    <div>
        Here are your account details:
        <ul style="padding-left: 16px">
            <li><strong>Email:</strong> {{ $data['subadminemail'] }}</li>
            <li><strong>Password:</strong> {{ $data['password'] }}</li>
        </ul>
        <div>
            <p style="width: 160px;margin:auto"><a href="{{ url('/admin-login') }}"
                    style="padding:5px 10px;color:rgb(253, 253, 253);background:black;border-radius:5px;text-decoration:none">Click
                    here to Login </a></p>
        </div>
    </div>
    <div>
        <div style="padding-top: 10px">
            Thanks,<br>
            Drivrr
        </div>
    </div>
@endcomponent
