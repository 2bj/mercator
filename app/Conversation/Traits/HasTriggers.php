<?php

namespace App\Conversation\Traits;

trait HasTriggers
{

    protected $triggers = [];

    protected function addTrigger(string $value)
    {
        $this->triggers[] = $value;

        return $this;
    }

    protected function getTriggers(): array
    {
        return $this->triggers;
    }

    protected function hasTrigger(string $value): bool
    {
        return in_array($value, $this->triggers);
    }

}