<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SSLCommerz
{
    protected string $baseURL;
    protected string $storeId;
    protected string $storePassword;

    public function __construct()
    {
        $this->baseURL = 'https://sandbox.sslcommerz.com';
        $this->storeId = config('sslcommerz.storeId');
        $this->storePassword = config('sslcommerz.storePassword');
    }

    public function initPayment()
    {
        $payload = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount'=> 10,
            'currency'=>'BDT',
            'tran_id' => Str::random(16),
            'product_category'=>'laptop',
            'ipn_url'=>'https://www.youtube.com',
            'success_url'=>'https://www.youtube.com',
            'fail_url'=>'https://www.youtube.com',
            'cancel_url'=>'https://www.youtube.com',
        ];


        $response = Http::asForm()->post($this->baseURL.'/gwprocess/v4/api.php',$payload);

        return $response->json()['GatewayPageURL'];
    }
}
