@component('mail::message')
# Activation Status

@if ($status == 1)
Your account has been activated.
@else
Your account has been De-activated.
@endif
{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
