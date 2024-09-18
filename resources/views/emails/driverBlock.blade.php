@component('mail::message')
    <div style="text-align:center;">
       <img src="{{ asset('public/admin/assets/img/blacklogo.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    Dear {{ $data['drivername'] }},
    You have been blocked by the Admin due to your Violation Of Our Policies.
    <div>
        Concerning this account,:
        <ul style="padding-left: 16px">
            <li><strong>The Associated Email is:</strong> {{ $data['driveremail'] }}</li>
            <li><strong>Reason:</strong> {{ $data['block_reason'] }}</li>
        </ul>
    </div>
    <div>
        <div style="padding-top: 10px">
            Thanks,<br>
            Drivrr
        </div>
    </div>
@endcomponent
