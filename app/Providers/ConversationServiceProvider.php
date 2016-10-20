<?php

namespace App\Providers;

use App\Conversation\Conversation;
use Illuminate\Support\ServiceProvider;

class ConversationServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(Conversation::class, function () {
            return new Conversation(config('conversation.flows'));
        });
    }

}