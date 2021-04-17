<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

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
        $input = $request->validated();
    
        $token = $this->service->login($input['email'], $input['password']);

        return ( new UserResource(auth()->user()))->additional($token);
    }
}
