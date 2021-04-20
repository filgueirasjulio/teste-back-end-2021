<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeUpdateRequest;
use App\Services\UserService;

class MeController extends Controller
{
    private $service;
    
    /**
     * __construct
     *
     * @param  mixed $service
     */
    public function __construct(UserService $service)
    {
        $this->middleware('auth:api');
        $this->service = $service;
    }
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $user = auth()->user();
  
        return responder()->success($user)->respond();
    }
    
    /**
     * update
     *
     * @param  MeUpdateRequest $request
     */
    public function update(MeUpdateRequest $request)
    {
        $inputs = $request->validated();
        $user = $this->service->updateMe(auth()->user(), $inputs);

        return responder()->success($user)->respond();
    }
}
