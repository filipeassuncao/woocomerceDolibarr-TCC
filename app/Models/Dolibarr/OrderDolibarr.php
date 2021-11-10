<?php

namespace App\Models\Dolibarr;

use Illuminate\Database\Eloquent\Model;

class OrderDolibarr extends Model
{
    protected $fillable = [
        'socid',
        'statut',
        'status',
        'cond_reglement_code',
        'mode_reglement',
        'mode_reglement_id',
        'mode_reglement_code',
        'date_creation',
        'date',
        'lines',
        'multicurrency_tx',
        'total_ht',
        'total_tva',
        'total_localtax1',
        'total_localtax2',
        'total_ttc',
        'specimen',
        'remise',
        'type',
        'modelpdf',
        'warehouse_id',
        'status',
        'multicurrency_code',
        'entity',
    ];
}
