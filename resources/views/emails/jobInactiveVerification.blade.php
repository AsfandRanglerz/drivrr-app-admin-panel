@component('mail::message')
    # Cancellation Status

    @if ($status == 1)
        Your request for job cancellation has been successfully approved.
    @else
        Unfortunately, your request for job cancellation has not been accepted.
    @endif

    Thanks,
    {{ config('app.name') }}
@endcomponent
