<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Model;

class CustomerDolibarr extends Model
{
    protected $fillable = [
        'entity',
        'name',
        'nameAlias',
        'address',
        'zip',
        'town',
        'status',
        'stateId',
        'stateCode',
        'state',
        'phone',
        'email',
        'idprof4',
        'client'
    ];
}

