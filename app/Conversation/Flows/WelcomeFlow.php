<?php

namespace App\Conversation\Flows;

use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\SendsMessages;

class WelcomeFlow extends AbstractFlow
{

    use HasTriggers, HasStates, SendsMessages;

    public function __construct()
    {
        // Triggers
        $this->addTrigger('/start');

        // States
        $this->addState('sayHello');
    }

    protected function sayHello()
    {
        $this->log('sayHello');

        $this->reply('Добро пожаловать в магазин "' . config('app.name') . '".');

        $this->runFlow(CategoryFlow::class);
    }

}