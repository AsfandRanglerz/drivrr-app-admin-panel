@component('mail::message')
# Hello

<p>Your job request has been canceled by {{$owner->fname . '.' . $owner->lname}}.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
