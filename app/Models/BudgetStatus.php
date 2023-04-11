<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetStatus extends Model
{
    protected $table 	= 'budget_status';

    protected $fillable = ['name', 'description'];

}