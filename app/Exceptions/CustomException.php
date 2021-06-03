<?php

namespace App\Exceptions;

use Throwable;

class CustomException extends \Exception
{
    protected $code, $message;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->message = $message;
        $this->code = $code;

        parent::__construct($message, $code, $previous);
    }

    public function getResponse()
    {
        return response()->json([
            'status' => $this->code,
            'message' => $this->message
        ], $this->code);
    }
}
