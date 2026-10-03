<?php

namespace App\Services;

use App\Models\Order;
use Xenon\NagadApi\Base;
use Xenon\NagadApi\Helper;

class Nagad{
    protected array $config;

    public function __construct()
    {
        $this->config = config('nagad');
    }

    public function createPaymentURL(string $invoice, $amount)
    {
        $nagad = new Base($this->config,[
            'amount'           => $amount,
            'invoice'          => $invoice,
            'merchantCallback' => route('nagad.callback'),
        ]);

        return $nagad->payNowWithoutRedirection($nagad);
    }

    public function verify(string $paymentRefId): array
    {
        $result = (new Helper($this->config))->verifyPayment($paymentRefId);

        return is_string($result) ? json_decode($result, true) : (array) $result;
    }

    public function isValidFor(Order $order, array $result): bool
    {
        return ($result['status'] ?? null) === 'Success'
            && ($result['orderId'] ?? null) === $order->invoice_no
            && (float) ($result['amount'] ?? 0) === (float) $order->total;
    }
}
