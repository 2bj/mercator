<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Log;
use Telegram;

class TelegramController extends Controller
{

    public function process(UserRepository $users, MessageRepository $messages)
    {
        $update = Telegram::bot()->getWebhookUpdate();

        Log::debug('Telegram.process', [
            'update' => $update,
        ]);

        $message = $update->getMessage();

        $user = $message->getFrom();

        // сохраняем или находим пользователя
        $user = $users->store(
            $user->getId(),
            $user->getFirstName() ?? '',
            $user->getLastName() ?? '',
            $user->getUsername() ?? ''
        );

        // сохраняем сообщение
        $messages->store($user, $message->getMessageId(), $message->getText() ?? '');

        if (hash_equals($message->getText(), '/start')) {
            Telegram::bot()->sendMessage([
                'chat_id' => $user->chat_id,
                'text' => 'Добро пожаловать в наш магазин.',
            ]);
        }
    }

}