<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSupport extends Model {

    protected $table    = 'app_support';

    protected $fillable = ['app_id', 'user_id', 'is_active'];

    protected $guarded  = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User', 'id', 'user_id');
    }

}
