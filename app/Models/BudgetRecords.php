<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetRecords extends Model
{
    protected $table 	= 'budget_records';

    protected $fillable = ['records', 'user_id', 'budget_id'];

}