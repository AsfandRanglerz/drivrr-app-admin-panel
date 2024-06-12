@component('mail::message')
    <div style="text-align:center;">
        <img src="https://ranglerzwp.xyz/drivrrapp/public/admin/assets/img/blacklogo.png" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    <div style="align-items: center">
        <p style="font-size: 24px; margin-bottom: 10px;">Payment Request Approval</p>
        <p style="font-size: 16px; ">Hello {{ $data['username'] }} </p>
        <p style="font-size: 16px; ">
            Your Payment Request Has Been Approved.
        </p>
        <p style="font-size: 16px; ">
            Here is the Proof:
        </p>
        <img src="{{ asset($data['image']) }}" alt="Payment Request Proof Image" style="max-width: 100%; margin-top: 10px;">
        <div>
            <p style="font-size: 16px;  margin-top: 10px;">
                Requested Amount: Rs:{{ $data['withdrawal_amount'] }}
            </p>
        </div>
    </div>
@endcomponent
