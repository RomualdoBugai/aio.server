<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankAccount extends Model
{
    protected $table    = 'user_bank_account';

    protected $fillable = [
        'user_id',
        'bank_account_id'
    ];

    protected $guarded  = [];

}
