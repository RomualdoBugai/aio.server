<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSmtp extends Model {

    protected $table    = 'app_smtp';

    protected $fillable = ['host', 'username', 'password', 'port', 'encryption', 'app_id'];

    protected $guarded  = [];

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id');
    }

}
