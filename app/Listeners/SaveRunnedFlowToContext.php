<?php

namespace App\Listeners;

use App\Conversation\Context;
use App\Events\FlowRunned;
use Log;

class SaveRunnedFlowToContext
{

    public function handle(FlowRunned $event)
    {
        $user = $event->getUser();
        $flow = $event->getFlow();
        $state = $event->getState();
        $options = $event->getOptions();
        Log::debug('SaveRunnedFlowToContext.handle', [
            'user' => $user->toArray(),
            'flow' => get_class($flow),
            'state' => $state,
            'options' => $options,
        ]);

        Context::save($user, $flow, $state, $options);
    }

}