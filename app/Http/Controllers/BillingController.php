<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillingRequest;
use App\Billing;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillingCreated;

class BillingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BillingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillingRequest $request)
    {
        $data = $this->fetchStore($request);

        $billing = Billing::create($data);
        $billing->generateNumber();

        $this->sendBilling($billing);

        return rest_api($billing, 201);
    }

    /**
     * Fetch request data.
     * 
     * @param BillingRequest $request
     * @return array
     */
    private function fetchStore($request)
    {
        return [
            'product_name'  => $request->product_name,
            'price'         => $request->price,
            'discount'      => $request->discount,
            'pay_before'    => $request->pay_before,
            'email'         => $request->email
        ];
    }

    /**
     * Send billing to email.
     * 
     * @param Billing $billing
     * @return void
     */
    private function sendBilling(Billing $billing)
    {
        Mail::to($billing->email)
            ->queue(new BillingCreated($billing));
    }
}
