<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\SaveCard;
use App\Services\Bkash;
use App\Services\PayPal;
use App\Services\SSLCommerz;
use App\Services\Stripe;
use App\Services\StripeIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class PaymentController extends Controller
{

    public function paymentMethodPage()
    {
        try {
            $card = SaveCard::where('user_id', Auth::id())->get();
            return view('payment.payment_method',['card'=>$card]);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function destroySavedCard(Request $request,$id)
    {
        try {
            $savedCard = SaveCard::find($id);

            $savedCard->delete();

            return redirect()->back()->with('success', 'Card deleted');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'is_default' => 'boolean',
        ]);

        try {
            $stripe = new StripeClient(config('stripe.stripe.secret'));
            $user = Auth::user();

            if (!$user->stripe_customer_id) {
                $customer = $stripe->customers->create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                $user->stripe_customer_id = $customer->id;
                $user->save();
            }

            $stripe->paymentMethods->attach($request->payment_method_id, [
                'customer' => $user->stripe_customer_id,
            ]);

            $pm = $stripe->paymentMethods->retrieve($request->payment_method_id);

            if ($request->boolean('is_default')) {
                SaveCard::where('user_id', $user->id)->update(['is_default' => false]);
            }

            $saved = SaveCard::create([
                'user_id' => $user->id,
                'stripe_payment_method_id' => $pm->id,
                'brand' => $pm->card->brand,
                'last_four' => $pm->card->last4,
                'exp_month' => $pm->card->exp_month,
                'exp_year' => $pm->card->exp_year,
                'is_default' => $request->boolean('is_default'),
            ]);

            return response()->json([
                'message' => 'Payment method successfully updated.',
                'card' => [
                    'brand' => $pm->card->brand,
                    'last_four' => $pm->card->last4,
                    'exp_month' => $pm->card->exp_month,
                    'exp_year' => $pm->card->exp_year,
                ],
            ]);


        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }
    }
    public function checkout(Request $request, ?Stripe $stripe, ?Bkash $bkash, ?StripeIntent $stripeIntent, ?SSLCommerz $commerz, ?PayPal $payPal)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'total_amount' => 'required',
                'provider'=>'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            if ($request->provider == 'cod'){
                Payment::create([
                    'order_id'=>$request->order_id,
                    'provider'=>$request->provider,
                    'status'=>'paid',
                ]);

                Order::where('id',$request->order_id)->update([
                    'status'=>'paid'
                ]);
            }
            elseif ($request->provider == 'stripe'){
                $order = Order::find($request->order_id);
                $savedCard = Auth::user()->savedOneCards()->first();

                if ($savedCard) {
                    $result = $stripeIntent->payWithSavedCard($order, $savedCard);

                    if ($result['status'] === 'succeeded') {
                        Payment::create([
                            'order_id' => $order->id,
                            'provider' => 'stripe',
                            'status' => 'paid',
                            'transaction_id' => $result['payment_intent']->id,
                            'raw_response' => json_encode($result['payment_intent']),
                        ]);

                        $order->update(['status' => 'paid']);

                        return redirect()->route('order.success', ['order' => $order->id])
                            ->with('success', 'Payment Successful');
                    }

                    if ($result['status'] === 'requires_action') {
                        return response()->json([
                            'requires_action' => true,
                            'client_secret' => $result['client_secret'],
                            'order_id' => $order->id,
                        ]);
                    }

                    return redirect()->back()->with('error', $result['message']);
                }

                Payment::create([
                    'order_id'=>$request->order_id,
                    'provider'=>$request->provider,
                    'status'=>'paid',
                ]);
                $url = $stripe->initiate($order);

                return redirect($url);
            }
            elseif ($request->provider == 'sslcommerz'){
                $ssl = $commerz->initPayment();

                return redirect()->away($ssl);
            }
            elseif ($request->provider == 'paypal'){
                $pay = $payPal->createOrder();
                return redirect()->away($pay);
            }
            else{
                $order = Order::find($request->order_id);
                Payment::create([
                    'order_id'=>$request->order_id,
                    'provider'=>$request->provider,
                    'status'=>'paid',
                ]);
                $token = $bkash->getToken();
                $url = $bkash->createPayment($order);
                return redirect($url);
            }

            return redirect()->back()->with('success', 'Payment Successful');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function success(Request $request, $order)
    {
        try {
            $orderModel = Order::findOrFail($order);


            $stripe = new StripeClient(config('stripe.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($request->query('session_id'));

            $orderModel->status = 'paid';
            $orderModel->save();

            $payment = Payment::where('order_id', $orderModel->id)->first();

            if ($payment) {
                $payment->status = 'paid';
                $payment->transaction_id = $session->payment_intent;
                $payment->raw_response = json_encode($session->toArray());
                $payment->save();
            }

            return view('payment.success',['order'=>$orderModel]);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function cancel(Request $request, $order)
    {
        try {
            return view('payment.cancel');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function callback(Request $request)
    {
        try {
            $paymentId = $request->query('paymentID');
            $status = $request->query('status');

            $bkash = new Bkash();
            $token = $bkash->getToken();
            $execute = $bkash->executePayment($paymentId);

            if (!$status == 'success'){
                return view('payment.cancel');
            }

            $orderId = $execute['payerReference'];

            $order = Order::find($orderId);
            $order->status = 'paid';
            $order->save();

            $payment = Payment::where('order_id', $orderId)->first();

            if ($payment) {
                $payment->status = 'paid';
                $payment->transaction_id = $execute['trxID'];
                $payment->raw_response = json_encode($execute);
                $payment->save();
            }

            return view('payment.success',['order'=>$order]);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
