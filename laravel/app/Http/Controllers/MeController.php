<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Actions\Users\getMeAction;
use App\Http\Requests\MeUpdateRequest;
use App\Transformers\Users\UserTransformer;

class MeController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $user = auth()->user();
  
        return responder()->success($user, UserTransformer::class)->respond();
    }
    
    /**
     * update
     *
     * @param  MeUpdateRequest $request
     * @param  getMeAction $action
     */
    public function update(MeUpdateRequest $request, getMeAction $action)
    {
        $user = $action->execute(auth()->user(), $request->validated());

        return responder()->success($user, UserTransformer::class)->respond();
    }
}
