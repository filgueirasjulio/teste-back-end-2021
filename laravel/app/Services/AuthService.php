<?php

namespace App\Services;

use App\Exceptions\LoginInvalidException;
use App\Exceptions\UserHasBeenTakenException;
use App\Models\User;

class AuthService
{    
    /**
     * login
     *
     * @param  string $email
     * @param  string $password
     * @return array
     */
    public function login(array $inputs):array
    {
        $login =  [
            'email' => $inputs["email"],
            'password' => $inputs["password"]
        ];
      
        if (!$token = auth()->attempt($login)) {
            throw new LoginInvalidException();
        }
    
        return  [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }
    
    /**
     * register
     *
     * @param  string $name
     * @param  string $email
     * @param  string $password
     * @return object
     */
    public function register(array $inputs):object
    {
        $userPassword = bcrypt($inputs["password"]);

        $user = User::create([
            'name' => $inputs["name"],
            'email'=> $inputs["email"],
            'password' => $userPassword,
        ]);

        return $user;
    }
}