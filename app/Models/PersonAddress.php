<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonAddress extends Model
{
    protected $table = 'person_address';

    protected $fillable = [
        'person_id',
        'street',
        'number',
        'district',
        'city',
        'state',
        'postal_code',
        'complement',
        'country',
        'is_active',
        'default'
    ];

    protected $guarded  = [];
}
