<?php

Route::post('/telegram/' . env('TELEGRAM_BOT_TOKEN'),
    ['as' => 'telegram.webhook', 'uses' => 'TelegramController@process']);