<?php

namespace App\Services;

use App\Billing;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillingCreated;
use App\Repositories\BillingRepository;
use PDF;
use Illuminate\Support\Facades\Storage;

class BillingService
{
    /**
     * The billing repository implementation.
     * 
     * @var BillingRepository
     */
    private $billingRepository;

    /**
     * Create new BillingService instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->billingRepository = new BillingRepository(new Billing());
    }

    /**
     * Store new billing and send billing to email.
     * 
     * @param array $data
     * @return Billing
     */
    public function createBilling($data)
    {
        $billing = $this->billingRepository->storeBilling($data);
        $this->sendBillingToEmail($billing, $billing->email);

        return $billing;
    }

    /**
     * Add send billing to email to queue job.
     * 
     * @param Billing $billing
     * @param string $email
     * @return void
     */
    private function sendBillingToEmail($billing, $email)
    {
        Mail::to($email)
            ->queue(new BillingCreated($billing));
    }

    /**
     * Update paid status of billing to 1.
     * 
     * @param Billing $billing
     * @return void
     */
    public function payBilling($billing)
    {
        $billing->pay();

        $this->generateBilling($billing);
        $this->sendPaidBillingToEmail($billing, $billing->email);
    }

    /**
     * Generate billing file as PDF.
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
     * Add send billing to email to queue job.
     * 
     * @param Billing $billing
     * @return void
     */
    private function sendPaidBillingToEmail($billing, $email)
    {
        Mail::to($email)
            ->queue(new BillingPaid($billing));
    }

    /**
     * Delete billing from repository
     * 
     * @param Billing $billing
     * @return void
     */
    public function cancelBilling($billing)
    {
        $billing->delete();
    }
}
