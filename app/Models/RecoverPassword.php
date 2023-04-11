<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecoverPassword extends Model
{
    protected $table    = 'recover_password';

    protected $fillable = ['user_id', 'app_id', 'verify', 'is_active'];

    protected $guarded  = [];

}
