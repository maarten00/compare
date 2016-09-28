<?php

namespace App\Listeners;

use App\Events\PageProcessed;
use App\Events\SomeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PageProcessedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PageProcessed  $event
     * @return void
     */
    public function handle(PageProcessed $event)
    {
        //
    }
}
