@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    Dear {{ $data['ownername'] }},
    You have been Unblocked by the Admin.
    <div>
        Concerning this account,:
        <ul style="padding-left: 16px">
            <li><strong>The Associated Email is:</strong> {{ $data['owneremail'] }}</li>
        </ul>
    </div>
    <div>
        <div style="padding-top: 10px">
            Thanks,<br>
            Drivrr
        </div>
    </div>
@endcomponent
