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
use App\Repositories\BillingRepository;
use App\Services\BillingService;

class BillingController extends Controller
{
    /**
     * The billing repository.
     * 
     * @var BillingRepository
     */
    private $billingRepository;

    /**
     * The billing service.
     * 
     * @var BillingService
     */
    private $billingService;

    /**
     * Create new BillingController instance.
     * 
     * @param BillingRepository $billingRepository
     * @param BillingService $billingService
     * @return void
     */
    public function __construct(BillingRepository $billingRepository, BillingService $billingService)
    {
        $this->billingRepository = $billingRepository;
        $this->billingService = $billingService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BillingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillingRequest $request)
    {
        $data = $this->fetchStore($request);

        $billing = $this->billingService->createBilling($data);
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
     * Update billing paid to 1.
     * 
     * @param string $number
     * @throws BillingNotFoundException
     * @throws BillingExpiredException
     * @return Response
     */
    public function pay($number)
    {
        $billing = $this->billingRepository->findByNumber($number);
        if (!$billing) {
            throw new BillingNotFoundException();
        }

        if (!$billing->isValid()) {
            throw new BillingExpiredException();
        }

        $this->billingService->payBilling($billing);
        return rest_api($billing);
    }

    /**
     * Cancel billing.
     *
     * @param  string $number
     * @throws BillingNotFoundException
     * @return \Illuminate\Http\Response
     */
    public function cancel($number)
    {
        $billing = $this->billingRepository->findByNumber($number);
        if (!$billing) {
            throw new BillingNotFoundException();
        }

        $this->billingService->cancelBilling($billing);
        return rest_api("Billing Canceled");
    }
    
}
