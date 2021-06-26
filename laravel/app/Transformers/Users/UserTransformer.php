<?php

namespace App\Transformers\Users;

use App\Models\User;
use Flugg\Responder\Transformers\Transformer;

class UserTransformer extends Transformer
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
      'e-mail' => $user->email
    ];
  }
}
