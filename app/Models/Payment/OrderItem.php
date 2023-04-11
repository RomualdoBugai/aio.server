<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_item';

    protected $fillable = ['quantity', 'amount', 'description', 'product_code', 'order_id'];

}