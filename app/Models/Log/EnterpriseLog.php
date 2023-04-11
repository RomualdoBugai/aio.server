<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

class EnterpriseLog extends Model
{
    protected $table    = 'enterprise_log';

    protected $fillable = [
        'enterprise_id',
        'user_id',
        'app_id',
        'message',
        'table',
        'table_id'
    ];

    protected $guarded  = [];

    public function user()
    {
       return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function enterprise()
    {
        return $this->hasOne('App\Models\Enterprise', 'id', 'enterprise_id');
    }

    public function app()
    {
        return $this->hasOne('App\Models\App', 'id', 'app_id');
    }

}
