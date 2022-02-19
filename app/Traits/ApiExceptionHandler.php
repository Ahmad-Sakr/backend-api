<?php


namespace App\Traits;


use App\Helpers\ResponseMessages;
use Illuminate\Http\Response;
use Exception;

trait ApiExceptionHandler
{
    protected function handleNotFoundException()
    {
        return $this->error(ResponseMessages::NOT_FOUND, Response::HTTP_NOT_FOUND);
    }

    protected function handleException(Exception $e)
    {
        return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function handleDifferentUserException()
    {
        return $this->error(ResponseMessages::UNAUTHORIZED, Response::HTTP_UNAUTHORIZED);
    }
}
