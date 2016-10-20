<?php

namespace App\Conversation\Flows;

use App\Conversation\Traits\HasOptions;
use App\Conversation\Traits\HasStates;
use App\Conversation\Traits\HasTriggers;
use App\Conversation\Traits\SendsMessages;
use App\Services\CategoryService;
use Schema\Record;

class CategoryFlow extends AbstractFlow
{

    use HasTriggers, HasStates, HasOptions, SendsMessages;

    public function __construct()
    {
        // States
        $this
            ->addState('showParent')
            ->addState('showChildren');

        // Options
        $this->addOption('parent_id');
    }

    protected function showParent()
    {
        $parentId = $this->getOption('parent_id');
        $this->log('showParent', ['parent_id' => $parentId]);

        $buttons = [];
        foreach ($this->categories() as $category) {
            if ($category->offsetGet('parent_id') == $parentId) {
                $buttons[] = $category->offsetGet('name');
            }
        }

        $this->reply('Список категорий', $buttons);
    }

    protected function showChildren()
    {
        $this->log('showChildren');
        /**
         * @var Record $record
         */
        $category = collect($this->categories())->first(function (Record $record) {
            return hash_equals($record->offsetGet('name'), $this->message->text);
        });

        if (is_null($category)) {
            return;
        }

        $this->log('showChildren.parent_id', ['id' => $category->offsetGet('id')]);

        $this->remember('parent_id', $category->offsetGet('id'));
        $this->runState('showParent');
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