<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;
    protected $encryptionKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.flutterwave.com/v3';
        $this->secretKey = config('flutterwave.secret_key');
        $this->publicKey = config('flutterwave.public_key');
        $this->encryptionKey = config('flutterwave.encryption_key');
    }

    /**
     * Initialize payment
     */
    public function initializePayment(array $data)
    {
        $payload = [
            'tx_ref' => $data['transaction_reference'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'NGN',
            'payment_options' => 'card, banktransfer, ussd',
            'redirect_url' => $data['redirect_url'],
            'customer' => [
                'email' => $data['customer_email'],
                'name' => $data['customer_name'],
                'phonenumber' => $data['customer_phone'] ?? '',
            ],
            'customizations' => [
                'title' => $data['title'] ?? config('app.name'),
                'description' => $data['description'] ?? 'Bus Ticket Payment',
            ],
            'meta' => $data['meta'] ?? [],
        ];

        try {
            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/payments', $payload);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Flutterwave Payment Initialization Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Verify transaction
     */
    public function verifyTransaction($transactionId)
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get($this->baseUrl . '/transactions/' . $transactionId . '/verify');

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Flutterwave Transaction Verification Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate transaction reference
     */
    public function generateTransactionReference()
    {
        return 'TXN_' . time() . '_' . uniqid();
    }
}