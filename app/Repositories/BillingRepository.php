<?php

namespace App\Repositories;

use App\Billing;

class BillingRepository
{
    /**
     * The billing model implementation.
     * 
     * @var Billing $model
     */
    private $model;

    /**
     * Create new BillingRepository instance.
     * 
     * @param Billing $billing
     * @return void
     */
    public function __construct(Billing $billing)
    {
        $this->model = $billing;
    }

    /**
     * Store new billing and generate billing number.
     * 
     * @param array $data
     * @return Billing
     */
    public function storeBilling($data)
    {
        $billing = Billing::create($data);
        $billing->generateNumber();

        return $billing;
    }

    /**
     * Get billing by it's number.
     * 
     * @param string $number
     * @return Billing
     */
    public function findByNumber($number)
    {
        return $this->model->where('billing_number', $number);
    }
}
