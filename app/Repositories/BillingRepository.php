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
}
