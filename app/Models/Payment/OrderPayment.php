<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payment';

    protected $fillable = ['amount_received', 'order_id'];

}