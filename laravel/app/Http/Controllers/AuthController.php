<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Actions\Auth\loginAction;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Transformers\Auth\LoginTransformer;
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
        $user = $action->execute($request->validated());

        return responder()->success($user, LoginTransformer::class)->respond();
    }

    /**
     * register
     *
     * @param  AuthRegisterRequest $request
     */
    public function register(AuthRegisterRequest $request)
    {
        $inputs = $request->validated();

        $user = $this->service->register($inputs);

        return responder()->success($user->toArray())->respond();
    }

    /**
     * verifyEmail
     *
     * @param  AuthVerifyEmailRequest $request
     * @throws VerifyEmailTokenInvalidException
     */
    public function verifyEmail(AuthVerifyEmailRequest $request)
    {
        $input = $request->validated();

        $user = $this->service->verifyEmail($input["token"]);

        return responder()->success($user->toArray())->respond();
    }

    /**
     * forgotPassword
     *
     * @param  AuthForgotPasswordRequest $request
     * @return void
     */
    public function forgotPassword(AuthForgotPasswordRequest $request)
    {
        $input = $request->validated();

        $this->service->forgotPassword($input["email"]);
        
        return responder()->success(['E-mail para recuperação de senha enviado!'])->respond();
    }

    public function resetPassword(AuthResetPasswordRequest $request)
    {
        $inputs = $request->validated();

        $this->service->resetPassword($inputs);

        return responder()->success(['Senha recuperada com sucesso'])->respond();
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
