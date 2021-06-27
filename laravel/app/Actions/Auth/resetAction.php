<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\PasswordReset;
use App\Support\Contracts\Action\ActionInterface;
use App\Exceptions\ResetPasswordTokenInvalidException;

class resetAction implements ActionInterface
{
    /**
     *
     * @param  array $array
     * @return object
     */
    public function execute(array $inputs):object
    {
        $passwordReset = PasswordReset::where('email', $inputs["email"])->where('token', $inputs["token"])->first();

        if (empty($passwordReset)) {
            throw new ResetPasswordTokenInvalidException();
        }

        $user = User::where('email', $inputs["email"])->firstOrFail();
        $user->password = bcrypt($inputs["password"]);
        $user->save();

        PasswordReset::where('email', $inputs["email"])->delete();

        return $user;
    }
}
