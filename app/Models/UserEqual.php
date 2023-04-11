<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEqual extends Model
{
    protected $table   = 'user_equal';

    protected $fillable = ['user_id', 'equal_user_id', 'app_id'];

    protected $guarded  = [];

    public function equals()
    {
        return $this->hasMany('App\Models\User');
    }

}
