<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Bkash{
    protected string $base_url;
    protected string $token;

    public function __construct()
    {
        $this->base_url = config('bkash.url');
        $this->token = '';
    }

    public function getToken()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'username'=>config('bkash.username'),
            'password'=>config('bkash.password'),
        ])->post($this->base_url.'/tokenized/checkout/token/grant',[
            'app_key'=>config('bkash.app_key'),
            'app_secret'=>config('bkash.app_secret'),
        ]);

        if ($response->ok()) {
            $this->token = $response->json()['id_token'];
        }else{
            return $response->json();
        }

        return $this->token;
    }

    public function createPayment(Order $order)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->token,
            'X-App-Key'=>config('bkash.app_key'),
        ])->post($this->base_url.'/tokenized/checkout/create',[
            'mode'=>'0011',
            'payerReference'=>$order->id,
            'callbackURL'=>route('bkash.callback'),
            'amount'=>$order->total_amount,
            'currency'=>'BDT',
            'intent'=>'sale',
            'merchantInvoiceNumber'=>'ORD-'.Str::random(6)
        ]);

        if ($response->ok()) {
            $data = $response->json()['bkashURL'];
        }else{
            return $response->json();
        }

        return $data;
    }

    public function executePayment($paymentId)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization'=>$this->token,
            'X-App-Key'=>config('bkash.app_key'),
        ])->post($this->base_url.'/tokenized/checkout/execute',[
            'paymentID'=>$paymentId,
        ]);
        if ($response->ok()) {
            $data = $response->json();
        }else{
            return $response->json();
        }
        return $data;
    }
}
