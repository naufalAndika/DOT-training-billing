<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Billing;

class BillingPaid extends Mailable
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
     * @param Billing
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
        return $this->from('ap.andikaputra2000@gmail.com')
            ->view('emails.billing_paid_mail')
            ->attach(storage_path('app/billing_' . $this->billing->id . '.pdf'), [
                'as' => 'your_billing.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
