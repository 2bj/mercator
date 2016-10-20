<?php

namespace App\Conversation\Flows;

use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\InteractsWithContext;
use App\Entities\Message;
use App\Entities\User;
use App\Exceptions\ConversationException;
use App\Traits\Loggable;

/**
 * Class AbstractFlow
 *
 * @method getNextState(string $current = null)
 * @method hasTrigger(string $value)
 *
 * @package App\Conversation\Flows
 */
abstract class AbstractFlow
{

    use Loggable, InteractsWithContext;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Message
     */
    protected $message;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Handle Flow
     *
     * @throws ConversationException
     */
    public function handle()
    {
        $this->log('handle', [
            'user' => $this->user->id,
            'message' => $this->message->text,
            'traits' => [
                'states' => $this->usesStates(),
                'triggers' => $this->usesTriggers(),
            ],
        ]);

        $this->validate();

        // Search in States
        $this->log('isFlowInContext', [$this->isFlowInContext($this)]);
        if ($this->usesStates() && $this->isFlowInContext($this)) {
            $state = $this->getNextState($this->context()->getState());

            if (is_null($state)) {
                $this->clearContext();
                throw new ConversationException('Next state is not defined.');
            }
            $this->runState($state);
            return true;
        }

        // Search in Triggers
        $this->log('hasTrigger', [$this->hasTrigger($this->message->text)]);
        if ($this->usesTriggers() && $this->hasTrigger($this->message->text)) {
            $state = $this->getNextState();
            $this->runState($state);
            return true;
        }

        return false;
    }

    /**
     * @param string $flow
     * @param string $state
     */
    protected function runFlow($flow, string $state = null)
    {
        $this->clearContext();

        /**
         * @var AbstractFlow $flow
         */
        $flow = app($flow);
        $flow->setUser($this->user);
        $flow->setMessage($this->message);

        $state = $state ?? $flow->getNextState();
        $flow->runState($state);
    }

    /**
     * Run State
     *
     * @param string $state
     * @throws ConversationException
     */
    protected function runState(string $state)
    {
        $this->log('runState', [
            'state' => $state,
        ]);

        // Run provided State
        $this->setContext($this, $state, $this->context()->getOptions());
        $this->$state();
    }

    private function validate()
    {
        // Context has another flow
        if (
            $this->context()->hasFlow() &&
            get_class($this->context()->getFlow()) !== get_class($this)
        ) {
            throw new ConversationException('Context has another flow.');
        }
    }

    private function usesStates(): bool
    {
        return in_array(HasStates::class, class_uses($this));
    }

    private function usesTriggers(): bool
    {
        return in_array(HasTriggers::class, class_uses($this));
    }

}