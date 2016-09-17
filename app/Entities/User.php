<?php

namespace App\Entities;

use Illuminate\Notifications\Notifiable;

/**
 * App\Entities\User
 *
 * @property integer $id
 * @property integer $chat_id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @mixin \Eloquent
 */
class User extends AbstractEntity
{
    use Notifiable;

    protected $fillable = [
        'chat_id',
        'first_name',
        'last_name',
        'username',
    ];

}
