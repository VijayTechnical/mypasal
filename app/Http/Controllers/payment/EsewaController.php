<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\Esewa;
use App\Models\UserPack;
use Illuminate\Http\Request;

class EsewaController extends Controller
{
    /**
     * @param Request $request
     */

    public function initiation($ref, Request $request)
    {
        $total = UserPack::where('ref', $ref)->sum('price');

        $gateway = with(new Esewa);

        try {
            $response = $gateway->purchase([
                'amount' => $gateway->formatAmount($total),
                'totalAmount' => $gateway->formatAmount($total),
                'productCode' => $ref,
                'failedUrl' => $gateway->getFailedUrl($ref),
                'returnUrl' => $gateway->getReturnUrl($ref),
            ], $request);
            if ($response->isRedirect()) {
                $response->redirect();
            }

        } catch (\Exception $e) {
            return response()->json('Payment failed,please try again!!!');

        }
    }

    public function completed($ref, Request $request)
    {
        $total = UserPack::where('ref', $ref)->sum('price');

        $gateway = with(new Esewa);

        $response = $gateway->verifyPayment([
            'amount' => $gateway->formatAmount($total),
            'referenceNumber' => $request->get('refId'),
            'productCode' => $request->get('oid'),
        ], $request);
        if ($response->isSuccessful()) {
            UserPack::where('ref', $ref)->update([
                'payment_status' => 1,
            ]);
            return response()->json('Payment successful!');

        }
        return response()->json('Payment failed,please try again!!!');

    }

    /**
     * @param $order_id
     * @param Request $request
     */
    public function failed($ref, Request $request)
    {

        return response()->json('Payment failed,please try again!!!');

    }
}
