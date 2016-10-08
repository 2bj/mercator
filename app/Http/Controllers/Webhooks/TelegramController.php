<?php

namespace App\Http\Controllers\Webhooks;

use App\Conversation\Conversation;
use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Log;
use Telegram;

class TelegramController extends Controller
{

    public function process(
        UserRepository $users,
        MessageRepository $messages,
        Conversation $conversation
    ) {
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
        $message = $messages->store($user, $message->getMessageId(), $message->getText() ?? '');

        $conversation->start($user, $message);
    }

}