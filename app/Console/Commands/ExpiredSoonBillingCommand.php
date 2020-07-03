<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExpiredSoonBilling;

class ExpiredSoonBillingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:expired_soon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expired billing in 10 minutes checker';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenMinutesBeforeNow = Carbon::now()->subMinutes(10);
        $expiredSoonBillings = Billing::where('pay_before', '<=', $tenMinutesBeforeNow)->where('paid', 0)->get();

        foreach($expiredSoonBillings as $billing) {
            Mail::to($billing->email)
                ->queue(new ExpiredSoonBilling($billing));
        }
    }
}
