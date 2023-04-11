<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class PagSeguroNotification extends Model
{
    protected $table = 'pag_seguro_notification';

    protected $fillable = ['json', 'order_id'];
}
