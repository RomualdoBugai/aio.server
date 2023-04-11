<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class OrderError extends Model
{
    protected $table = 'order_error';

    protected $fillable = ['json', 'order_id'];

}