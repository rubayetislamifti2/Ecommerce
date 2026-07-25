<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\Bkash;
use App\Services\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function checkout(Request $request, ?Stripe $stripe, ?Bkash $bkash)
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
            }elseif ($request->provider == 'stripe'){
                $order = Order::find($request->order_id);
                Payment::create([
                    'order_id'=>$request->order_id,
                    'provider'=>$request->provider,
                    'status'=>'paid',
                ]);
                $url = $stripe->initiate($order);

                return redirect($url);
            }else{
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
