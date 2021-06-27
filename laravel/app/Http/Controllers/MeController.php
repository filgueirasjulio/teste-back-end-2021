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
       try {
            $user = auth()->user();
  
            return responder()->success($user, UserTransformer::class)->respond();
       
        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }     
    }
    
    /**
     * update
     *
     * @param  MeUpdateRequest $request
     * @param  getMeAction $action
     */
    public function update(MeUpdateRequest $request, getMeAction $action)
    {
        try {
            $user = $action->execute(auth()->user(), $request->validated());

            return responder()->success($user, UserTransformer::class)->respond();
        
        } catch(\Exception $exception) {

            return responder()
            ->error($exception->getCode(), $exception->getMessage())
            ->respond(400);
        }
    }
}
