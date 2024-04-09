<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class AuthTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tel' => $user->tel,
            'birthday' => $user->birthday,
        ];
    }
}
