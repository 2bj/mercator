<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractFlow;
use App\Conversation\Flows\CategoryFlow;
use App\Conversation\Flows\WelcomeFlow;
use App\Entities\Message;
use App\Entities\User;
use Log;

class Conversation
{

    protected $flows = [
        WelcomeFlow::class,
        CategoryFlow::class,
    ];

    public function start(User $user, Message $message)
    {
        Log::debug('Conversation.start', [
            'user' => $user->toArray(),
            'message' => $message->toArray(),
        ]);

        $context = Context::get($user);

        foreach ($this->flows as $flow) {
            /**
             * @var AbstractFlow $flow
             */
            $flow = app($flow);

            $flow->setUser($user);
            $flow->setMessage($message);
            $flow->setContext($context);

            $flow->run();
        }

    }

}