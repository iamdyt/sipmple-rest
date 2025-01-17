<?php

namespace App\Exceptions;

use App\Traits\HttpStatusResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    use HttpStatusResponse;

    /**
     * @param  string  $message
     * @param  int  $code
     */
    public function __construct($message = 'Unable to process your request', $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render(Request $request): JsonResponse
    {
        return $this->failure($this->message, code: $this->code);
    }
}
