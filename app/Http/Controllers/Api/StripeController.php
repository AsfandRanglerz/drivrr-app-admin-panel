<?php

namespace App\Http\Controllers\Api;

use Stripe\Payout;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;
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
    public function payoutClient(Request $request,)
    {

        $data = $request->json()->all();
        $amount = $data['amount'];
        $currency = $data['currency'];

        Stripe::setApiKey('your_stripe_secret_key');

        $customer = Customer::create();
        $ephemeralKey = EphemeralKey::create(
            ['customer' => $customer->id],
            ['api_version' => '2022-08-01']
        );
        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $customer->id,
        ]);
    }
    // Add other methods similar to the Node.js routes...
}
