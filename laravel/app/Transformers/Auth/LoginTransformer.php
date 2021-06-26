<?php

namespace App\Transformers\Auth;

use App\Models\User;
use Flugg\Responder\Transformers\Transformer;

class LoginTransformer extends Transformer
{
  /**
   * Transform the model.
   *
   * @param  \App\User $user
   * @return array
   */
  public function transform(User $user)
  {
    return [
      'id' => (int) $user->id,
      'name' => $user->name,
      'e-mail' => $user->email,
      'access_token' => $user->token,
      'token_type' => $user->token_type
    ];
  }
}
