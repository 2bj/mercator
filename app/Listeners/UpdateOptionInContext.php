<?php

namespace App\Listeners;

use App\Conversation\Context;
use App\Events\OptionChanged;
use Log;

class UpdateOptionInContext
{

    public function handle(OptionChanged $event)
    {
        $user = $event->getUser();
        $key = $event->getKey();
        $value = $event->getValue();
        Log::debug('UpdateOptionInContext.handle', [
            'user' => $user->toArray(),
            'key' => $key,
            'value' => $value,
        ]);

        Context::update($user, [$key => $value,]);
    }

}