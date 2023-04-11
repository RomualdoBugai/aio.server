<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmEmail extends Model
{
    protected $table     = "confirm_email";

    protected $filltable = [
        'user_id',
        'email',
        'is_confirmed',
        'verify'
    ];

    protected $guarded   = [];

}
