<?php

namespace App\Providers;

use App\Events\FlowRunned;
use App\Events\OptionChanged;
use App\Listeners\SaveRunnedFlowToContext;
use App\Listeners\UpdateOptionInContext;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        FlowRunned::class => [
            SaveRunnedFlowToContext::class,
        ],
        OptionChanged::class => [
            UpdateOptionInContext::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
