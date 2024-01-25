@component('mail::message')
    <div style="background-color: #4CAF50; color: #fff; padding: 20px; border-radius: 10px;">
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Congratulations!</h2>
        <p style="font-size: 16px;">You have successfully signed up with {{ config('app.name') }}.</p>

        @if ($status === 'Owner')
            <p style="font-size: 16px;">You are now registered as a {{ $status }}.</p>
        @else
            <p style="font-size: 16px;">You are now registered as a {{ $status }}.</p>
        @endif

        <p style="font-size: 16px; margin-top: 20px;">Thanks for joining us!</p>
        <p style="font-size: 14px;">{{ config('app.name') }}</p>
    </div>
@endcomponent
