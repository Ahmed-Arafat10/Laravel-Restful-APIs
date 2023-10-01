<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'attributes' => [
                'User_ID' => (int)$this->id,
                'Name' => (string)$this->name,
                'Email' => (string)$this->email,
                'isVerified' => (boolean)$this->verified,
                'isAdmin' => (boolean)$this->admin,
                'creationDate' => (string)$this->created_at,
                'lastChange' => (string)$this->updated_at,
                'deletedDate' => (string)$this->deleted_at,
            ],
            'relationships' => [],
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $this->id)
                ]
            ]
        ];
    }

    private static array $attributes = [
        'User_ID' => 'id',
        'Name' => 'name',
        'Email' => 'email',
        'isVerified' => 'verified',
        'isAdmin' => 'admin',
        'creationDate' => 'created_at',
        'lastChange' => 'updated_at',
        'deletedDate' => 'deleted_at',
        'Password' => 'password',
        'PassCon' => 'password_confirmation',
    ];

    public static function originalAttribute($index)
    {
        return static::$attributes[$index] ?? null;
    }

    public static function validationAttributes($rules)
    {
        $res = [];
        $newArr = array_flip(static::$attributes);
        foreach ($rules as $key => $val) {
            if (isset($newArr[$key]))
                $res[$newArr[$key]] = $val;
        }
        return $res;
    }

    public static function originalRequestAtt(Request &$request)
    {
        $res = [];
        foreach ($request->request->all() as $input => $val) {
            $k = static::originalAttribute($input);
            if($k) $res[$k] = $val;
        }
        $request->replace($res);
    }
}

