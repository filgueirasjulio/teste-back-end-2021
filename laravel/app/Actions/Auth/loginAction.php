<?php

namespace App\Actions\Auth;

use App\Exceptions\LoginInvalidException;
use App\Support\Contracts\Action\ActionInterface;

class loginAction implements ActionInterface
{
    /**
     *
     * @param  string $email
     * @param  string $password
     * @return object
     */
    public function execute(array $inputs):object
    {
        $login =  [
            'email' => $inputs["email"],
            'password' => $inputs["password"]
        ];

        if (!$token = auth()->attempt($login)) {
            throw new LoginInvalidException();
        }

        $user = auth()->user();
        $user->token = $token;
        $user->token_type = 'Bearer';

        return $user;
    }
}
