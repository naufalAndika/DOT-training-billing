<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillingRequest;
use App\Billing;

class BillingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BillingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillingRequest $request)
    {
        $data = [
            'product_name'  => $request->product_name,
            'price'         => $request->price,
            'discount'      => $request->discount,
            'pay_before'    => $request->pay_before,
            'email'         => $request->email
        ];

        $billing = Billing::create($data);
        $billing->generateNumber();

        return rest_api($billing, 201);
    }
}
