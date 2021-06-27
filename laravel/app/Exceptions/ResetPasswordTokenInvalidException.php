<?php

namespace App\Exceptions;

use Exception;

class ResetPasswordTokenInvalidException extends Exception
{
    protected $message = 'Reset password token não é válido';

    public function render()
    {
        return response()->json([
            'error' =>  class_basename($this),
            'message' => $this->getMessage()
        ], 401);
    }
}
