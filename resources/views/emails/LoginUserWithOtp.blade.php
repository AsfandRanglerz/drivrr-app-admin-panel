@component('mail::message')
# OTP.


<p>{{ $OTP }}</p>



Thanks,<br>
from:{{ config('app.name') }}
@endcomponent
