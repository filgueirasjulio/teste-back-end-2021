<?php

namespace App\Http\Controllers;

use App\Actions\Auth\forgotAction;
use App\Services\AuthService;
use App\Actions\Auth\loginAction;
use App\Actions\Auth\registerAction;
use App\Actions\Auth\resetAction;
use App\Actions\Auth\verifyEmailAction;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Transformers\Auth\LoginTransformer;
use App\Transformers\Users\UserTransformer;
use App\Http\Requests\AuthVerifyEmailRequest;
use App\Http\Requests\AuthResetPasswordRequest;
use App\Http\Requests\AuthForgotPasswordRequest;

class AuthController extends Controller
{
    private $service;

    /**
     * __construct
     *
     * @param  AuthService $service
     * @return void
     */
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * login
     *
     * @param  AuthLoginRequest $request
     * @throws LoginInvalidException
     */
    public function login(AuthLoginRequest $request, loginAction $action)
    {
        try {
            $user = $action->execute($request->validated());

            return responder()
                    ->success($user, LoginTransformer::class)
                    ->meta(['message' => trans('messages.auth.login')])
                    ->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }

    }

    /**
     * register
     *
     * @param  AuthRegisterRequest $request
     */
    public function register(AuthRegisterRequest $request, registerAction $action)
    {
        try {
            $user = $action->execute($request->validated());
   
            return responder()
                   ->success($user, UserTransformer::class)
                   ->meta(['message' => trans('messages.auth.register')])
                   ->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }  
    }

    /**
     * verifyEmail
     *
     * @param  AuthVerifyEmailRequest $request
     * @throws VerifyEmailTokenInvalidException
     */
    public function verifyEmail(AuthVerifyEmailRequest $request, verifyEmailAction $action)
    {
        try {
            $user = $action->execute($request->validated());

            return responder()
                   ->success($user, UserTransformer::class)
                   ->meta(['message' => trans('messages.auth.verify_email')])
                   ->respond();

        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }  
    }

    /**
     * forgotPassword
     *
     * @param  AuthForgotPasswordRequest $request
     * @return void
     */
    public function forgotPassword(AuthForgotPasswordRequest $request, forgotAction $action)
    {
        try {
            $user = $action->execute($request->validated());
        
            return responder()
                ->success($user, UserTransformer::class)
                ->meta(['message' => trans('messages.auth.forgot_password')])
                ->respond();
                
        }  catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }  
    }

    public function resetPassword(AuthResetPasswordRequest $request, resetAction $action)
    {
        try {
            $user = $action->execute($request->validated());
        
            return responder()
                ->success($user, UserTransformer::class)
                ->meta(['message' => trans('messages.auth.reset_password')])
                ->respond();

        }  catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        } 
    }

    public function logout()
    {
        auth()->logout();

        return responder()->success(['Usuário deslogado com sucesso'])->respond();
    }

    public function refresh()
    {
        $newToken = auth()->refresh();

        return responder()->success(['Novo token gerado com sucesso'])->respond();
    }
}
