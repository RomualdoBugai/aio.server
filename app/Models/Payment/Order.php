<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';

    protected $fillable = ['code', 'amount_total', 'quantity_total', 'json', 'user_id', 'payment_method_id', 'order_status_id', 'app_id', 'plan_id'];

}