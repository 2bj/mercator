<?php

namespace App\Repositories;

use App\Entities\User;

class UserRepository extends AbstractRepository
{

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function store(int $id, string $firstName, string $lastName, string $username): User
    {
        $values = [
            'chat_id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
        ];

        return $this->entity->firstOrCreate(['chat_id' => $id], $values);
    }

}