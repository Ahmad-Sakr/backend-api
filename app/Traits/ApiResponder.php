<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/*
|--------------------------------------------------------------------------
| ApiResponder Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponder
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return JsonResponse
     */
    protected function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status'    => 'Success',
            'message'   => $message,
            'data'      => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return JsonResponse
     */
    protected function error($message = null, $code = 500, $data = null)
    {
        return response()->json([
            'status'    => 'Error',
            'message'   => $message,
            'data'      => $data
        ], $code);
    }

}
