<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Model;

class ProductDolibarr extends Model
{
    protected $fillable = [
        'ref',
        'label',
        'description',
        'type',
        'price',
        'price_ttc',
        'status_buy',
        'price_min',
        'fk_default_warehouse',
        'status',
        'finished',
        'weight',
        'length',
        'width',
        'height'
    ];
}
