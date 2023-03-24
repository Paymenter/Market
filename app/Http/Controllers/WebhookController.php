<?php
namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook call.
     *
     * @param  Request  $request
     * @return Response
     */
    public function handleStripeWebhook(Request $request)
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
        $this->postToDiscord("Order {$order->id} has been paid for {$paymentIntent->amount} {$paymentIntent->currency} by {$paymentIntent->customer}.")
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

    public function handlePaypalWebhook(Request $request)
    {
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        $req = 'cmd=_notify-validate';
        foreach ($myPost as $key => $value) {
            $value = urlencode($value);

            $req .= "&$key=$value";
        }

        $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        if (!($res = curl_exec($ch))) {
            Log::error("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            exit;
        }
        curl_close($ch);
        if ($res == 'VERIFIED') {
            $order = Orders::where('paypal_id', $myPost['txn_id'])->first();
            if(!$order){
                $this->postToDiscord("Failed to find order for {$myPost['txn_id']}");
                return;
            }
            $order->status = 'paid';
            $order->save();
        }
        header("HTTP/1.1 200 OK");
    }

}
