<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthVerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

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
     * @return UserResource
     */
    public function login(AuthLoginRequest $request)
    {
        $inputs = $request->validated();
    
        $token = $this->service->login($inputs);

        return ( new UserResource(auth()->user()))->additional($token);
    }
    
    /**
     * register
     *
     * @param  AuthRegisterRequest $request
     * @return UserResource
     */
    public function register(AuthRegisterRequest $request)
    {
        $inputs = $request->validated();

        $user = $this->service->register($inputs);

        return new UserResource($user);
    }
    
    /**
     * verifyEmail
     *
     * @param  AuthVerifyEmailRequest $request
     * @throws VerifyEmailTokenInvalidException
     * @return UserResource
     */
    public function verifyEmail(AuthVerifyEmailRequest $request)
    {
        $input = $request->validated();

        $user = $this->service->verifyEmail($input["token"]);

        return new UserResource($user);
    }
}
