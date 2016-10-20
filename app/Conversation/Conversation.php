<?php

namespace App\Conversation;

use App\Conversation\Flows\AbstractFlow;
use App\Conversation\Traits\InteractsWithContext;
use App\Entities\Message;
use App\Entities\User;
use App\Exceptions\ConversationException;
use App\Traits\Loggable;

class Conversation
{

    use Loggable, InteractsWithContext;

    private $flows;

    public function __construct(array $flows)
    {
        $this->flows = $flows;
    }

    public function start(User $user, Message $message)
    {
        $this->log('start', [
            'user' => $user->id,
            'message' => $message->text,
        ]);

        $this->user = $user;

        try {
            $context = $this->context();

            if ($context->hasFlow()) {
                $flow = $context->getFlow();
                $flow->setUser($this->user);
                $flow->setMessage($message);
                $flow->handle();
                return;
            }

            foreach ($this->flows as $flow) {
                /**
                 * @var AbstractFlow $flow
                 */
                $flow = app($flow);
                $flow->setUser($this->user);
                $flow->setMessage($message);
                if ($flow->handle()) {
                    break;
                }
            }
        } catch (ConversationException $exception) {
            $this->log('exception', ['message' => $exception->getMessage()]);
        }

    }

}