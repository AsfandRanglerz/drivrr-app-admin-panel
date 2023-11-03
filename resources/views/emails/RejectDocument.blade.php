@component('mail::message')
# Hello,


Here is the reson:
<h4>{{$reason}}</h4>
Therefore, Your document has been rejected.
Kindly submit it again.
Thanks,<br>
{{ config('app.name') }}
@endcomponent
