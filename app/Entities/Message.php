<?php

namespace App\Entities;

/**
 * App\Entities\Message
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $external_id
 * @property string $text
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class Message extends AbstractEntity
{

    protected $fillable = [
        'user_id',
        'external_id',
        'text',
    ];

}