<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('test.midtrans-testing');
    }

    public function payment()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = 'SB-Mid-server--4E4BvO1n_azJL_Vbc5GxjjL';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        
        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 10000,
            ),
            'item_details' => array(
                array(
                    'id'       => 'item1',
                    'price'    => 10000,
                    'quantity' => 1,
                    'name'     => 'Pulsa'
                ),
            ),
            'enabled_payments' => [
                "permata_va",
                "bca_va",
                "bni_va",
                "bri_va",
                "other_va",
                "gopay",
                "indomaret",
                "alfamart"
            ],
            'customer_details' => array(
                'first_name' => 'budi',
                'last_name' => 'pratama',
                'email' => 'budi.pra@example.com',
                'phone' => '08111222333',
            ),
        );
        
        // $snapToken = \Midtrans\Snap::getSnapToken($params);
        // $snapUrl = \Midtrans\Snap::getSnapUrl($params);
        $snap = \Midtrans\Snap::createTransaction($params);

        return \response()->json($snap);
        // return \response()->json([
        //     'status' => 200,
        //     'token' => $snapToken,
        //     'redirect_url' => $snapUrl
        // ]);
    }
}
