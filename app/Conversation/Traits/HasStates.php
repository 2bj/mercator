<?php

namespace App\Conversation\Traits;

trait HasStates
{

    protected $states = [];

    protected function addState(string $value)
    {
        $this->states[] = $value;

        return $this;
    }

    protected function getStates(): array
    {
        return $this->states;
    }

    /**
     * @param string|null $current
     * @return string|null
     */
    protected function getNextState($current = null)
    {
        $states = $this->getStates();

        if (is_null($current)) {
            return $states[0];
        }

        $current = collect($this->getStates())->search(function ($item) use ($current) {
            return $item == $current;
        });

        if (isset($states[$current + 1])) {
            return $states[$current + 1];
        }

        return null;
    }

}