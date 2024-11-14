@component('mail::message')
    <div style="text-align:center;">
        <img src="{{ asset('public/admin/assets/img/blacklogo.png') }}" alt="App Icon"
            style="vertical-align: middle; margin-bottom: -3px; height: 50px; margin-bottom: 35px">
        <h3>Account Deletion Request</h3>
    </div>
    Dear Admin,

    A user has requested to delete their account. Below are the details of the user requesting deletion:

    <div>
        <ul style="padding-left: 16px;">
            <li><strong>User Name:</strong> {{ $data['username'] }}</li>
            <li><strong>Email:</strong> {{ $data['useremail'] }}</li>
            <li><strong>Requested Date:</strong> {{ \Carbon\Carbon::now()->toFormattedDateString() }}</li>
        </ul>
    </div>

    <div style="padding-top: 10px;">
        Please review and take appropriate action.
    </div>

    <div style="padding-top: 10px;">
        Thanks,<br>
        Drivrr
    </div>
@endcomponent
