<div style="color: #fff; padding: 20px; border-radius: 10px; background-color: #198754;">
    <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px;">Withdrawal Approval</h2>

    <p style="font-size: 16px; color: #fff;">Hello Driver</p>

    <p style="font-size: 18px; color: #fff; font-weight: bold;">
        Your withdrawal request has been approved.
    </p>

    <p style="font-size: 16px; color: #fff;">
        Here is the proof:
    </p>

    <img src="{{ asset($data['image']) }}" alt="Withdrawal Proof Image" style="max-width: 100%; margin-top: 10px;">

    <p style="font-size: 16px; color: #fff; margin-top: 10px;">
        Withdrawal Amount: ${{ $data['amount'] }}
    </p>

    <p style="font-size: 16px; color: #fff; margin-top: 20px;">
        Thank you for using our service.
    </p>
</div>
