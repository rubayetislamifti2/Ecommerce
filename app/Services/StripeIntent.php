<?php

namespace App\Services;

use App\Models\Order;
use App\Models\SaveCard;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;

class StripeIntent
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.stripe.secret'));
    }

    /**
     * User er saved card diye order er amount charge koro (user online thakle).
     */
    public function payWithSavedCard(Order $order, SaveCard $savedCard): array
    {
        $user = $order->user ?? $savedCard->user;

        if (!$user || !$user->stripe_customer_id) {
            throw new \Exception('User does not have a Stripe customer profile.');
        }

        if ($savedCard->user_id !== $user->id) {
            throw new \Exception('This card does not belong to the user.');
        }

        try {
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $this->toCents($order->total_amount),
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'payment_method' => $savedCard->stripe_payment_method_id,
                'off_session' => false,
                'confirm' => true,
                'metadata' => ['order_id' => $order->id],
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            return $this->handleIntentResult($intent);

        } catch (CardException $e) {
            return [
                'status' => 'failed',
                'payment_intent' => null,
                'message' => $e->getError()->message ?? 'Card was declined.',
            ];
        } catch (ApiErrorException $e) {
            return [
                'status' => 'failed',
                'payment_intent' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Off-session charge (user online na thakle, e.g. subscription renewal).
     */
    public function payOffSession(Order $order, SaveCard $savedCard): array
    {
        $user = $order->user ?? $savedCard->user;

        try {
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $this->toCents($order->total_amount),
                'currency' => 'usd',
                'customer' => $user->stripe_customer_id,
                'payment_method' => $savedCard->stripe_payment_method_id,
                'off_session' => true,
                'confirm' => true,
                'metadata' => ['order_id' => $order->id],
            ]);

            return $this->handleIntentResult($intent);

        } catch (CardException $e) {
            return [
                'status' => 'requires_action',
                'payment_intent' => $e->getError()->payment_intent->id ?? null,
                'message' => 'This card requires additional authentication.',
            ];
        } catch (ApiErrorException $e) {
            return [
                'status' => 'failed',
                'payment_intent' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 3D Secure confirm howar por status abar check korar jonno.
     */
    public function confirmIntent(string $paymentIntentId): array
    {
        try {
            $intent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            return $this->handleIntentResult($intent);
        } catch (ApiErrorException $e) {
            return [
                'status' => 'failed',
                'payment_intent' => null,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function handleIntentResult($intent): array
    {
        return match ($intent->status) {
            'succeeded' => [
                'status' => 'succeeded',
                'payment_intent' => $intent,
                'message' => 'Payment completed successfully.',
            ],
            'requires_action', 'requires_source_action' => [
                'status' => 'requires_action',
                'payment_intent' => $intent,
                'client_secret' => $intent->client_secret,
                'message' => 'Additional authentication required (3D Secure).',
            ],
            default => [
                'status' => 'failed',
                'payment_intent' => $intent,
                'message' => 'Payment could not be completed. Status: ' . $intent->status,
            ],
        };
    }

    protected function toCents(float|string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}
