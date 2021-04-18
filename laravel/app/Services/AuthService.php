<?php

namespace App\Services;

use App\Events\ForgotPassword;
use App\Models\User;
use Illuminate\Support\Str;
use App\Events\UserRegistered;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\ResetPasswordTokenInvalidException;
use App\Models\PasswordReset;
use App\Exceptions\UserHasBeenTakenException;
use App\Exceptions\VerifyEmailTokenInvalidException;


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
            'confirmation_token' => Str::random(60)
        ]);
    
        event(new UserRegistered($user));

        return $user;
    }
    
    /**
     * verifyEmail
     *
     * @param  string $token
     * @return object
     */
    public function verifyEmail(string $token)
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
    
    /**
     * forgotPassword
     *
     * @param  string $email
     * @return void
     */
    public function forgotPassword(string $email)
    {
        $user = User::where('email', $email)->firstOrFail();
        $token = Str::random(60);

        PasswordReset::create([
            'email' => $user->email,
            'token' => $token
        ]);

        event(new ForgotPassword($user, $token));

        return '';
    }

    public function resetPassword(array $inputs)
    {
        $passwordReset = PasswordReset::where('email', $inputs["email"])->where('token', $inputs["token"])->first();
        if (empty($passwordReset)) {
            throw new ResetPasswordTokenInvalidException();
        }

        $user = User::where('email', $inputs["email"])->firstOrFail();
        $user->password = bcrypt($inputs["password"]);
        $user->save();

        PasswordReset::where('email', $inputs["email"])->delete();

        return '';
    }
}