<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class PagSeguro extends Model
{
    protected $table = 'pag_seguro';

    protected $fillable = ['code', 'fee_amount', 'net_amount', 'extra_amount', 'json', 'order_id'];

}
