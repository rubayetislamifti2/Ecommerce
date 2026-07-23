<?php

namespace App\Services;

use App\Models\Order;
use Stripe\StripeClient;

class Stripe{

    protected StripeClient $stripe;

    public function __construct(StripeClient $stripe){
        $this->stripe = new StripeClient(config('stripe.stripe.secret'));
    }
    public function initiate(Order $order)
    {
        $lineItems = [];

        foreach ($order->orderItems as $orderItem) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $orderItem->product->name,
                    ],
                    'unit_amount' => $orderItem->price * 100,
                ],
                'quantity' => $orderItem->quantity,
            ];
        }

        $session = $this->stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('order.success',['order'=>$order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('order.cancel',['order'=>$order->id]),
            'payment_method_types' => ['card'],
            'metadata'=>[
                'order_id'=>$order->id,
                'provider'=>'stripe',
            ]
        ]);

        return $session->url;
    }
}
