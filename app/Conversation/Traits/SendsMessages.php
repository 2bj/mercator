<?php

namespace App\Conversation\Traits;

use App\Entities\User;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;

trait SendsMessages
{

    /**
     * @var User
     */
    protected $user;

    public function reply(string $message, array $buttons = [])
    {
        $params = [
            'chat_id' => $this->user->chat_id,
            'text' => $message,
        ];

        if (count($buttons) > 0) {
            $buttons = collect($buttons)->map(function ($value) {
                return [$value];
            });

            $params['reply_markup'] = Keyboard::make([
                'keyboard' => $buttons->toArray(),
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
            ]);
        }

        Telegram::bot()->sendMessage($params);
    }

}