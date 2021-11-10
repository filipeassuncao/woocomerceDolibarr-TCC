<?php

namespace App\Events;


class ProductsInOrderEvent extends Event
{
    private $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function data()
    {
        return $this->data;
    }
}
