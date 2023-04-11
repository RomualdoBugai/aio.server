<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table 	= 'budget';

    protected $fillable = ['name', 'email', 'phone', 'budget_status_id', 'app_id', 'user_id', 'plan_id'];

}