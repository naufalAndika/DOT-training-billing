<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillingRequest;
use App\Billing;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillingCreated;
use App\Mail\BillingPaid;
use App\Exceptions\BillingNotFoundException;
use App\Exceptions\BillingExpiredException;
use Illuminate\Support\Facades\Storage;
use PDF;

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

    /**
     * Update billing paid to 1.
     * 
     * @param string $number
     * @throws BillingNotFoundException
     * @throws BillingExpiredException
     * @return Response
     */
    public function pay($number)
    {
        $billing = Billing::findByNumber($number);
        if (!$billing) {
            throw new BillingNotFoundException();
        }

        if (!$billing->isValid()) {
            throw new BillingExpiredException();
        }

        $billing->pay();

        $this->generateBilling($billing);
        $this->sendPaidBilling($billing);

        return rest_api($billing);
    }

    /**
     * Download billing file as PDF.
     * 
     * @param Billing $billing
     * @return PDF
     */
    private function generateBilling(Billing $billing)
    {
        $data = $this->fetchPDF($billing);
        $billingPDF = PDF::loadview('emails.billing_paid', $data);
        Storage::put("billing_$billing->id.pdf", $billingPDF->output());
    }

    /**
     * Fetch data for PDF file.
     * 
     * @param Billing $billing
     * @return array
     */
    private function fetchPDF($billing)
    {
        return [
            'id'                => $billing->id,
            'product_name'      => $billing->product_name,
            'price'             => $billing->price,
            'discount'          => $billing->discount,
            'total'             => $billing->price * (100 - $billing->discount) / 100,
        ];
    }

    /**
     * Send paid billing.
     * 
     * @param Billing $billing
     * @return void
     */
    private function sendPaidBilling(Billing $billing)
    {
        Mail::to($billing->email)
            ->queue(new BillingPaid($billing));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $number
     * @throws BillingNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function cancel($number)
    {
        $billing = Billing::findByNumber($number);
        if (!$billing) {
            throw new BillingNotFoundException();
        }

        $billing->delete();

        return rest_api("Billing Deleted");
    }
    
}
