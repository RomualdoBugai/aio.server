<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table    = 'user_address';

    protected $fillable = [
        'user_id',
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
