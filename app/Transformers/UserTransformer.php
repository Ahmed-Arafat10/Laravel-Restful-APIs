<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'UserID' => (int)$user->id,
            'Name' => (string)$user->name,
            'email' => (string)$user->email,
            'isVerified' => (boolean)$user->verified,
            'isAdmin' => (boolean)$user->admin,
            'craetionDate' => (string)$user->created_at,
            'lastChangeDate' => (string)$user->updated_at,
            'deletedDate' => isset($user->deleted_at) ? (string)$user->deleted_at : null,

        ];
    }
}
