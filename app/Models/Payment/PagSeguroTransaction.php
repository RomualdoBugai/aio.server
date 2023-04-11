<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class PagSeguroTransaction extends Model
{
    protected $table = 'pag_seguro_transaction';

    protected $fillable = ['code', 'status', 'name', 'order_id'];
}
