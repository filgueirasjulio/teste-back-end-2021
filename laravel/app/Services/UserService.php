<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function updateMe(User $user, array $input)
    {
        if (!empty($input["password"])) {
            $input["password"] = bcrypt($input["password"]);
        }

        $user->fill($input);
        $user->save();

        return $user->fresh();
    }
}