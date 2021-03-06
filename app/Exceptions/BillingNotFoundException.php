<?php

namespace App\Exceptions;

use Exception;

class BillingNotFoundException extends Exception
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return rest_error("Billing Not Found", null, 404, 404);
    }
}
