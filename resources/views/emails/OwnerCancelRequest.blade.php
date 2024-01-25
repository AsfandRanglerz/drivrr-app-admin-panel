@component('mail::message')
    <div style="background-color: #f7f7f7; padding: 20px; border-radius: 10px;">
        <h2 style="color: #333; font-size: 24px; font-weight: bold; margin-bottom: 10px;">Important Update</h2>
        <p style="color: #666; font-size: 16px;">Unfortunately, your job request has been canceled by <span
                style="color: #e74c3c; font-weight: bold;">{{ $owner->fname . ' ' . $owner->lname }}</span>.</p>

        <p style="color: #666; font-size: 16px; margin-top: 20px;">Thanks for your understanding!</p>
        <p style="color: #888; font-size: 14px;">{{ config('app.name') }}</p>
    </div>
@endcomponent
