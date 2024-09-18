@component('mail::message')
    <div style="text-align:center;">
      <img src="{{ asset('public/admin/assets/img/blacklogo.png') }}" alt="App Icon"
            style="vertical-align: middle;margin-bottom: -3px;height: 50px;margin-bottom: 35px">
        <h3>Welcome to Drivrr</h3>
    </div>
    <div>
        Your One-Time Password (OTP) is: {{ $OTP }}
        <br>
        Thanks,<br>
         Drivrr
    </div>
@endcomponent
