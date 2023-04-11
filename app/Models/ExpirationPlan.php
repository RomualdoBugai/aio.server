<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpirationPlan extends Model
{
    protected $table    = 'expiration_plan';

    protected $fillable = ['user_id', 'app_id', 'start_date', 'end_date', 'is_active'];

}
