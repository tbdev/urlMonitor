<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class AbstractInternalException extends Exception
{
    protected string $title = 'Internal Exception';
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function render(): JsonResponse
    {
        return new JsonResponse([
            'code' => $this->code,
            'data' => null,
            'errors' => $this->message
        ], $this->code);
    }
}
