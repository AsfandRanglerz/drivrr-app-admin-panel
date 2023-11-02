@component('mail::message')
# Welcome

@if ($id == 3)
<p>You are successfully registerded as a driver.</p>
@elseif ($id == 2)
<p>You are successfully registerded as a business owner.</p>
@else
<p>You are successfully registerded as a admin.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
