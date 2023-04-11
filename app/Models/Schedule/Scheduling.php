<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

class Scheduling extends Model
{
    protected $table    = 'scheduling';

    protected $fillable = [
        'title',
        'description',
        'is_public',
        'is_active',
        'start_at',
        'end_at',
        'coordinates'
    ];

    protected $guarded  = [];

}
