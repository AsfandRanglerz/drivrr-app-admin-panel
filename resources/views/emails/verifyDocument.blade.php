@component('mail::message')
# Document Activation Status

The body of your message.

@if ($verify['is_active'] == 1)
Your {{ $verify['name'] }} has been approved.
@else
Your {{ $verify['name'] }} has been rejected.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
