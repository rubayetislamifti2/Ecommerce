<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayPal
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('paypal.baseUrl');
        $this->clientId = config('paypal.clientId');
        $this->clientSecret = config('paypal.clientSecret');
    }

    public function getToken()
    {
       $response = Http::asForm()
           ->withBasicAuth($this->clientId, $this->clientSecret)
           ->post($this->baseUrl . '/v1/oauth2/token', [
               'grant_type'=>'client_credentials'
           ]);
       $data = [
           'access_token' => $response->json()['access_token'],
           'token_type' => $response->json()['token_type']
       ];

       return $data;
    }
    public function createOrder()
    {
        $token = $this->getToken();
        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount'=>[
                        'currency_code'=>'USD',
                        'value'=>'100.00'
                    ]
                ]
            ],
            'application_context' => [
                'return_url' => route('order.success'),
                'cancel_url' => route('order.cancel'),
                'brand_name' => config('app.name'),
                'landing_page' => 'LOGIN',
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
            ],
        ];
        $response = Http::withHeaders([
            'Authorization'=>$token['token_type'].' '.$token['access_token']
        ])->post($this->baseUrl . '/v2/checkout/orders', []);
        $data = $response->json();

        $approveUrl = collect($data['links'])
            ->firstWhere('rel', 'approve')['href'];

        return $approveUrl;
    }
}
