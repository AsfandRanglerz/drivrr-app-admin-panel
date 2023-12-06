@component('mail::message')
    Hi,

    Your job request has been Accepted by {{ $owner->fname . ' ' . $owner->lname }}.
    Thanks!<br>
    {{ config('app.name') }}
@endcomponent
