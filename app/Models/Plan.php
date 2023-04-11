<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table    = 'plan';

    protected $fillable = ['name', 'user_limit', 'enterprise_limit', 'upload_limit', 'send_file_email', 'is_active', 'allow_choose', 'price', 'product_code', 'app_id'];

    protected $guarded  = [];

    public function app()
    {
        return $this->belongsTo('App\Models\App', 'app_id', 'id');
    }
}
