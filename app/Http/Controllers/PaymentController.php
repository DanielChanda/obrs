<?php

namespace App\Http\Controllers;

use App\Services\FlutterwaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $flutterwaveService;

    public function __construct(FlutterwaveService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }

    /**
     * Initialize payment
     */
    public function initialize(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        $transactionRef = $this->flutterwaveService->generateTransactionReference();

        $paymentData = [
            'transaction_reference' => $transactionRef,
            'amount' => $request->amount,
            'currency' => $request->currency ?? 'NGN',
            'customer_email' => $request->email,
            'customer_name' => $request->name,
            'customer_phone' => $request->phone,
            'redirect_url' => route('payment.callback'),
            'meta' => [
                'user_id' => auth()->id(),
                'order_id' => $request->order_id,
            ],
        ];

        $response = $this->flutterwaveService->initializePayment($paymentData);

        if ($response['status'] === 'success') {
            // Store transaction reference in session or database
            session(['transaction_reference' => $transactionRef]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Payment initialized successfully',
                'data' => [
                    'payment_url' => $response['data']['link'],
                    'transaction_reference' => $transactionRef,
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to initialize payment',
            'data' => $response
        ], 400);
    }

    /**
     * Payment callback
     */
    public function callback(Request $request)
    {
        $status = $request->query('status');
        $transactionId = $request->query('transaction_id');
        $txRef = $request->query('tx_ref');

        if ($status === 'successful') {
            // Verify the transaction
            $verification = $this->flutterwaveService->verifyTransaction($transactionId);

            if ($verification['status'] === 'success' && 
                $verification['data']['status'] === 'successful' &&
                $verification['data']['tx_ref'] === $txRef) {
                
                // Payment was successful
                return $this->handleSuccessfulPayment($verification['data']);
            }
        }

        return $this->handleFailedPayment();
    }

    /**
     * Handle successful payment
     */
    protected function handleSuccessfulPayment($paymentData)
    {
        // Update your database
        // Create order, update transaction status, etc.
        
        return redirect()->route('payment.success')
            ->with('success', 'Payment completed successfully!')
            ->with('payment_data', $paymentData);
    }

    /**
     * Handle failed payment
     */
    protected function handleFailedPayment()
    {
        return redirect()->route('payment.failed')
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     * Webhook handler
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('verif-hash');
        $payload = $request->getContent();

        // Validate webhook signature
        if (!config('flutterwave.secret_hash') || 
            $signature !== config('flutterwave.secret_hash')) {
            Log::warning('Flutterwave webhook: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->all();

        Log::info('Flutterwave Webhook Received:', $event);

        // Handle different event types
        switch ($event['event']) {
            case 'charge.completed':
                $this->handleChargeCompleted($event['data']);
                break;
            
            case 'transfer.completed':
                $this->handleTransferCompleted($event['data']);
                break;
            
            default:
                Log::info('Unhandled Flutterwave webhook event: ' . $event['event']);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Handle charge completed event
     */
    protected function handleChargeCompleted($data)
    {
        // Update your database with the payment status
        // This is where you confirm the payment and fulfill the order
        
        Log::info('Payment completed webhook:', $data);
    }

    /**
     * Success page
     */
    public function success()
    {
        return "view('payment.success')";
    }

    /**
     * Failed page
     */
    public function failed()
    {
        return "view('payment.failed')";
    }
}