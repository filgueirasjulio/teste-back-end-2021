<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Events\UserRegistered;
use App\Support\Contracts\Action\ActionInterface;

class registerAction implements ActionInterface
{
    /**
     *
     * @param  string $email
     * @param  string $password
     * @return object
     */
    public function execute(array $inputs):object
    {
        $userPassword = bcrypt($inputs["password"]);

        $user = User::create([
            'name' => $inputs["name"],
            'email'=> $inputs["email"],
            'password' => $userPassword,
            'confirmation_token' => Str::random(60)
        ]);

        if (!$user) {
            throw new \Exception(trans('messages.auth.register_error'), 400);
        }
    
        event(new UserRegistered($user));

        return $user;
    }
}
