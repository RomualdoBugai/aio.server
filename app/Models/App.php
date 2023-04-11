<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App extends Model {
    protected $table    = 'app';

    protected $fillable = ['name', 'full_name', 'description', 'author', 'resume', 'email_support'];

    protected $guarded  = [];

    public function users()
    {
        return $this->hasMany('App\Models\UserApp', 'app_id', 'id');
    }

    public function plans()
    {
        return $this->hasMany('App\Models\Plan', 'app_id', 'id');
    }

    public function support()
    {
        return $this->hasMany('App\Models\AppSupport', 'app_id', 'id');
    }

    public function smtp()
    {
        return $this->hasOne('App\Models\AppSmtp', 'id', 'app_id');
    }

}
