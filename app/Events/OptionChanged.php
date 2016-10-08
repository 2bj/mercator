<?php

namespace App\Events;

use App\Entities\User;
use Illuminate\Queue\SerializesModels;

class OptionChanged
{
    use SerializesModels;

    protected $user;
    protected $key;
    protected $value;

    public function __construct(User $user, string $key, $value)
    {
        $this->user = $user;
        $this->key = $key;
        $this->value = $value;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

}
