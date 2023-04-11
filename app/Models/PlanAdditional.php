<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAdditional extends Model
{
    protected $table    = 'plan_additional';

    protected $fillable = ['name', 'is_active', 'price', 'product_code', 'app_id'];

}
