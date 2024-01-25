@component('mail::message')
    <div style="background-color: #f7f7f7; padding: 20px; border-radius: 10px;">
        <h2 style="color: #333; font-size: 24px; font-weight: bold; margin-bottom: 10px;">Great News!</h2>
        <p style="color: #666; font-size: 16px;">Your job request has been accepted by <span
                style="color: #3498db; font-weight: bold;">{{ $owner->fname . ' ' . $owner->lname }}</span>.</p>

        <p style="color: #666; font-size: 16px; margin-top: 20px;">Thanks!</p>
        <p style="color: #888; font-size: 14px;">{{ config('app.name') }}</p>
    </div>
@endcomponent
