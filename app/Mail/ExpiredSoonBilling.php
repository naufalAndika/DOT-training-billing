<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Billing;

class ExpiredSoonBilling extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Billing to be sent.
     * 
     * @var Billing
     */
    protected $billing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'id'                => $this->billing->id,
            'product_name'      => $this->billing->product_name,
            'price'             => $this->billing->price,
            'discount'          => $this->billing->discount,
            'total'             => $this->billing->price * (100 - $this->billing->discount) / 100,
            'pay_before'        => $this->billing->pay_before,
            'payment_link'      => url('/billings') . "/" . $this->billing->billing_number . "/pay",
            'cancel_link'       => url('/billings') . "/" . $this->billing->billing_number . "/cancel"
        ];

        return $this->from('ap.andikaputra2000@gmail.com')
            ->with($data)
            ->view('emails.expired_soon_billing');
    }
}
