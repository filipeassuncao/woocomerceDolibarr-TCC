<?php

namespace App\Listeners;

use App\Events\ProductsInOrderEvent;
use App\Jobs\OrderJob;

class ProductsInOrderListener
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


    public function handle(ProductsInOrderEvent $event)
    {
        dispatch(new OrderJob($event->data()));
    }
}
