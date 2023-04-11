<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPartner extends Model
{
    protected $table   = 'user_partner';

    protected $fillable = ['user_id', 'partner_user_id', 'rate'];

    protected $guarded  = [];

}
