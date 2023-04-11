<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';

    protected $fillable = ['name', 'description'];

}