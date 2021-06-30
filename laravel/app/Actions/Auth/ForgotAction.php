<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Events\ForgotPassword;
use App\Support\Contracts\Action\ActionInterface;

class ForgotAction implements ActionInterface
{
    /**
     *
     * @param  array $array
     * @return object
     */
    public function execute(array $inputs):object
    {
        $user = User::where('email', $inputs['email'])->firstOrFail();
        $token = Str::random(60);

        PasswordReset::create([
            'email' => $user->email,
            'token' => $token
        ]);

        event(new ForgotPassword($user, $token));
        
        return $user;
    }
}
