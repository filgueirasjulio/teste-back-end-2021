<?php

namespace App\Actions\Users;

use App\Support\Contracts\Action\ActionInterface;

class getMeAction implements ActionInterface
{
    public function execute($user, $request)
    {        
        if (!empty($request["password"])) {
            $input["password"] = bcrypt($request["password"]);
        }

        $user->fill($request);
        $user->save();

        return $user->fresh();
    }
}
