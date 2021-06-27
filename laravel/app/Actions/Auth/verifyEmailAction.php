<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Support\Contracts\Action\ActionInterface;
use App\Exceptions\VerifyEmailTokenInvalidException;

class verifyEmailAction implements ActionInterface
{
    /**
     *
     * @param  array $token
     * @return object
     */
    public function execute(array $token):object
    {
        $user = User::where('confirmation_token', $token)->first();
        if (empty($user)) {
            throw new VerifyEmailTokenInvalidException();   
        }

        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }
}
