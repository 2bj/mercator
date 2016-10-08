<?php

namespace App\Conversation\Flows;

use App\Entities\Message;
use App\Entities\User;
use App\Events\FlowRunned;
use App\Events\OptionChanged;
use InvalidArgumentException;
use Log;
use Telegram;
use Telegram\Bot\Api;

abstract class AbstractFlow
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Message
     */
    protected $message;

    protected $triggers = [];
    protected $states = ['first'];
    protected $options = [];

    protected $context = [];

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function setMessage(Message $message)
    {
        $this->message = $message;
    }

    public function setContext(array $context)
    {
        $this->context = $context;
    }

    public function getStates(): array
    {
        return $this->states;
    }

    /**
     * @param string|null $state
     *
     * @return bool
     */
    public function run($state = null): bool
    {
        Log::debug(static::class . '.run', [
            'user' => $this->user->toArray(),
            'message' => $this->message->toArray(),
            'state' => $state,
        ]);

        // в контексте указан другой flow
        if (isset($this->context['flow']) && $this->context['flow'] !== get_class($this)) {
            return false;
        }

        // перезаписываем значениями из контекста
        $this->options = array_merge($this->context['options'] ?? $this->options, $this->options);

        // передано значение state
        if (!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state, $this->options));
            $this->$state();
            return true;
        }

        // поиск по контексту
        $state = $this->findByContext();
        if (!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state, $this->options));
            $this->$state();
            return true;
        }

        // поиск по триггерам
        $state = $this->findByTrigger();
        if (!is_null($state)) {
            event(new FlowRunned($this->user, $this, $state, $this->options));
            $this->$state();
            return true;
        }

        return false;
    }

    private function findByContext()
    {
        $state = null;

        if (
            isset($this->context['flow']) &&
            isset($this->context['state']) &&
            class_exists($this->context['flow']) &&
            method_exists(app($this->context['flow']), $this->context['state'])
        ) {
            $flow = $this->getFlow($this->context['flow']);

            $states = $flow->getStates();
            $currentState = collect($states)->search($this->context['state']);
            $currentState = $states[$currentState];

            $nextState = $currentState + 1;

            if (isset($states[$nextState])) {
                $flow->run($states[$nextState]);

                return $states[$nextState];
            }
        }

        return null;
    }

    private function findByTrigger()
    {
        $state = null;

        foreach ($this->triggers as $trigger) {
            if (hash_equals($trigger, $this->message->text)) {
                $state = 'first';
            }
        }

        return $state;
    }

    protected function telegram(): Api
    {
        return Telegram::bot();
    }

    protected function jump(string $flow, string $state = 'first')
    {
        $this->getFlow($flow)->run($state);
    }

    protected function saveOption(string $key, $value)
    {
        event(new OptionChanged($this->user, $key, $value));
    }

    private function getFlow(string $flow): AbstractFlow
    {
        if (!class_exists($flow)) {
            throw new InvalidArgumentException('Flow does not exist.');
        }

        /**
         * @var AbstractFlow $flow
         */
        $flow = app($flow);

        $flow->setUser($this->user);
        $flow->setMessage($this->message);
        $flow->setContext($this->context);

        return $flow;
    }

    abstract protected function first();

}