<?php

namespace App\Http\Controllers\Api;

use Stripe\Payout;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    private $secretKey;
    private $publishableKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret_key');
        $this->publishableKey = config('services.stripe.publishable_key');

        Stripe::setApiKey($this->secretKey);
    }

    public function index()
    {
        return response()->json([
            'status' => 200,
            'message' => 'Working Fine',
        ]);
    }

    public function checkBalance()
    {
        $balance = \Stripe\Balance::retrieve();
        return response()->json([
            'status' => 200,
            'data' => $balance,
        ]);
    }

    public function payoutClient(Request $request)
    {
        try {
            $amount = $request->input('amount');
            $currency = $request->input('currency');

            $payout = Payout::create([
                'amount' => $amount,
                'currency' => $currency,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Working Fine',
                'data' => $payout,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Add other methods similar to the Node.js routes...
}
