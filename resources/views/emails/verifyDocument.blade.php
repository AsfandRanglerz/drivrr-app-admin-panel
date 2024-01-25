<div style="color: #fff; padding: 20px; border-radius: 10px;background-color:#198754;">
    <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Document Activation Status</h2>

    <p style="font-size: 16px;color:#fff">Hello,</p>

    @if ($verify['is_active'] == 1)
        <p style="font-size: 18px; color: #fff; font-weight: bold;">Your {{ $verify['name'] }} has been approved.</p>
        <p style="font-size: 16px;">You can now enjoy the benefits of an active {{ $verify['name'] }} on our platform.
        </p>
    @else
        <p style="font-size: 18px; color:#fff; font-weight: bold;">Unfortunately, your {{ $verify['name'] }} has been
            rejected.</p>
        <p style="font-size: 16px;color:#fff">Kindly review and resubmit the document. We appreciate your cooperation.
        </p>
    @endif

    <p style="font-size: 16px; margin-top: 20px;color:#fff">Thanks for your understanding!</p>
</div>
