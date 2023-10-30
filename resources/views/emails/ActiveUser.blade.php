@component('mail::message')
# Welcome

@if ($id == 3)
<p>You are successfully login as a driver.</p>
@endif
<p>You are successfully login as a business owner.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
