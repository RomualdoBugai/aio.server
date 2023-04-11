<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseAddress extends Model
{
    protected $table    = 'enterprise_address';

    protected $fillable = [
        'enterprise_id',
        'street',
        'number',
        'district',
        'city',
        'state',
        'postal_code',
        'complement',
        'country_id',
        'is_active',
        'default'
    ];

    protected $guarded  = [];
}
