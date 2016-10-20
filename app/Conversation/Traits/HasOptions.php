<?php

namespace App\Conversation\Traits;

use App\Conversation\Context;

/**
 * Class HasOptions
 *
 * @method Context context()
 *
 * @package App\Conversation\Traits
 */
trait HasOptions
{

    protected $options = [];

    protected function addOption(string $name, string $default = null)
    {
        $this->options[] = [
            'name' => $name,
            'default' => $default,
        ];

        return $this;
    }

    protected function getOptions(): array
    {
        return $this->options;
    }

    protected function getOption(string $name)
    {
        $option = collect($this->options)->first(function ($item) use ($name) {
            return $item['name'] == $name;
        });

        $context = $this->context();

        // Get value from context
        if (isset($context->getOptions()[$name])) {
            return $context->getOptions()[$name];
        }

        return $option['default'];
    }

}