<?php
namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook call.
     *
     * @param  Request  $request
     * @return Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
                $payload, env('STRIPE_WEBHOOK_SECRET')
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        }
        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                // Fulfill the purchase...
                $this->handlePaymentIntentSucceeded($session);
                break;
            case 'checkout.session.expired':
                $session = $event->data->object;
                // Send an email to the customer asking them to retry the payment
                $this->handlePaymentIntentPaymentFailed($session);
                break;
            case 'application_fee.created':
                $fee = $event->data->object;
                $this->postToDiscord("Collecting application fee of {$fee->amount} {$fee->currency} from {$fee->account} for {$fee->application}");
                break;
            }
        return response()->json(['success' => 'Webhook received!'], 200);
    }

    private function handlePaymentIntentSucceeded($paymentIntent){
        // Handle the successful payment intent.
        // The PaymentIntent object contains the payment information.
        $order = Orders::where('stripe_id', $paymentIntent->id)->first();
        if(!$order){
            $this->postToDiscord("Failed to find order for {$paymentIntent->id}");
            return;
        }
        $order->status = 'paid';
        $order->save();
    }

    private function handlePaymentIntentPaymentFailed($paymentIntent){
        // Handle the failed payment intent.
        // The PaymentIntent object contains the payment information.
        $order = Orders::where('stripe_id', $paymentIntent->id)->first();
        if(!$order){
            $this->postToDiscord("Failed to find order for {$paymentIntent->id}");
            return;
        }
        $order->status = 'failed';
        $order->save();
    }

    private function postToDiscord($message){
        $data = array("content" => $message, "username" => "Stripe");
        $curl = curl_init(env('DISCORD_WEBHOOK_URL'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ));
        $result = curl_exec($curl);
        curl_close($curl);
    }

}
