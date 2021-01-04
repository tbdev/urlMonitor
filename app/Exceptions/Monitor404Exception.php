<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class Monitor404Exception extends AbstractInternalException
{
    protected string $title = 'URL not exist!';
    protected $code = Response::HTTP_NOT_FOUND;
}
