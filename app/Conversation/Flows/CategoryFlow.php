<?php

namespace App\Conversation\Flows;

use App\Services\CategoryService;
use Log;
use Schema\Record;
use Telegram\Bot\Keyboard\Keyboard;

class CategoryFlow extends AbstractFlow
{

    protected $triggers = [];
    protected $states = ['first', 'navigate'];
    protected $options = [
        'parent_id' => null,
    ];

    protected function first()
    {
        $parentId = $this->options['parent_id'];
        Log::debug('CategoryFlow.first', ['parent_id' => $parentId]);

        $buttons = [];
        foreach ($this->categories() as $category) {
            if ($category->offsetGet('parent_id') == $parentId) {
                $buttons[] = [$category->offsetGet('name')];
            }
        }

        $this->telegram()->sendMessage([
            'chat_id' => $this->user->chat_id,
            'text' => 'Список категорий',
            'reply_markup' => Keyboard::make([
                'keyboard' => $buttons,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]),
        ]);
    }

    protected function navigate()
    {
        /**
         * @var Record $record
         */
        $category = collect($this->categories())->first(function (Record $record) {
            return hash_equals($record->offsetGet('name'), $this->message->text);
        });

        if (is_null($category)) {
            return;
        }

        $this->options = ['parent_id' => $category->offsetGet('id')];
        $this->first();
    }

    /**
     * @return Record[]
     */
    private function categories()
    {
        /**
         * @var CategoryService $services
         * @var Record[] $categories
         */
        $services = app(CategoryService::class);
        return $services->all()->records();
    }

}