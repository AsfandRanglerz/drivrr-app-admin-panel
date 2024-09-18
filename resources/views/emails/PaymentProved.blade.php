@component('mail::message')
    <div style="text-align:center;">
     <img src="{{ asset('public/admin/assets/img/blacklogo.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    <div style="align-items: center">
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Withdrawal Approval</h2>
        <p style="font-size: 16px; ">Hello Driver</p>
        <p style="font-size: 18px;  font-weight: bold;">
            Your withdrawal request has been approved.
        </p>
        <p style="font-size: 16px; ">
            Here is the proof:
        </p>
        <img src="{{ asset($data['image']) }}" alt="Withdrawal Proof Image" style="max-width: 100%; margin-top: 10px;">
        <div>
            <p style="font-size: 16px;  margin-top: 10px;">
                Withdrawal Amount: ${{ $data['amount'] }}
            </p>
        </div>
    </div>
@endcomponent
