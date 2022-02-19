<?php

namespace App\Exceptions;

use App\Helpers\ResponseMessages;
use App\Traits\ApiResponder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;
use Illuminate\Validation\ValidationException;

//use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponder;

    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()) {
            //Validation Exception
            if ($e instanceof ValidationException) {
                return $this->error(ResponseMessages::VALIDATION_FAILURE,Response::HTTP_UNPROCESSABLE_ENTITY, [
                    'errors' => $e->errors()
                ]);
            }

            //Model Not Found Exception
            if ($e instanceof ModelNotFoundException) {
                return $this->error(ResponseMessages::NOT_FOUND, Response::HTTP_NOT_FOUND);
            }

            //Unauthorized Exception
            if (($e instanceof AuthorizationException) || ($e instanceof AuthenticationException)) {
                return $this->error(ResponseMessages::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
            }
        }

        return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
