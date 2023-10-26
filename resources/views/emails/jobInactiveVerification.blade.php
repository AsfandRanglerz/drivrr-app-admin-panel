@component('mail::message')
    # Cancelation Status

    @if ($status == 1)
        Your Request For Job Cancelltion has be Successfully Approved .
    @else
        Your Request Are Not Accepted For job Cancellation.
    @endif

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
