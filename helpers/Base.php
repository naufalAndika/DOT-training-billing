<?php

/**
 * Create api response
 * 
 * @param mixed data
 * @param integer response code
 * 
 * @return Response
 */
if (!function_exists('rest_api')) {
    function rest_api($data, $code = 200)
    {
        $response['data'] = $data;

        return response()->json($response, $code);
    }
}

/**
 * Create error response
 * 
 * @param string message
 * @param mixed additional data
 * @param integer response code
 * @param integer error code
 * 
 * @return Response
 */
if (!function_exists('rest_error')) {
    function rest_error($message, $data = null, $code = 400, $errCode = null)
    {
        $response = [
            'error' => [
                'code'      => $code,
                'message'   => $message
            ]
        ];

        if ($data) {
            $response['error']['errors'] = $data;
        }

        if ($errCode) {
            $response['error']['code'] = $errCode;
        }

        return response()->json($response, $code);
    }
}
