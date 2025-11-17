<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans Configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
public function createPayment(Transaction $transaction)
{
    $orderId = $transaction->id;
    $amount = $transaction->total_price;

    // Midtrans payment parameters
    $transactionDetails = array(
        'order_id' => $orderId,
        'gross_amount' => $amount,
    );

    $items = array();
    foreach ($transaction->items as $item) {
        $items[] = array(
            'id' => $item->product->id,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'name' => $item->product->name,
        );
    }

    $customerDetails = array(
        'first_name'    => $transaction->user->name,
        'email'         => $transaction->user->email,
        'phone'         => $transaction->user->phone_number,
    );

    $params = array(
        'transaction_details' => $transactionDetails,
        'item_details'        => $items,
        'customer_details'    => $customerDetails,
    );

    // Request payment from Midtrans
    try {
        $snapToken = Snap::getSnapToken($params);
        return view('payment.redirect', compact('snapToken'));
    } catch (\Exception $e) {
        return back()->withErrors('Pembayaran gagal: ' . $e->getMessage());
    }
}

}
