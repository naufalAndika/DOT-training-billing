<?php

namespace App\Exceptions;

use Exception;

class BillingNotFoundException extends Exception
{
    public function render($request)
    {
        return rest_error("Billing Not Found", null, 404, 404);
    }
}
