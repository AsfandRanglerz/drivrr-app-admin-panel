@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    <h2 style="font-size: 24px;">Document Rejection Notice</h2>
    <div>
        <p style="font-size: 16px;">Dear {{ $data['drivername'] }} Unfortunately, your submitted License has been rejected for the following
            Reason:</p>
    </div>
    <div>
        <h4 style="font-size: 18px;">
            {{ $data['rejection_reason'] }}</h4>
        <p style="font-size: 16px; margin-top: 20px;">Kindly review and resubmit the document. We appreciate your
            cooperation.Thanks!</p>
    </div>
@endcomponent
