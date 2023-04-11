<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $table    = 'enterprise';

    protected $fillable = [
        'name',
        'fantasy_name',
        'national_code',
        'legal_nature',
        'last_update',
        'open_at',
        'is_matrix',
        'is_active',
        'status',
        'country_id'
    ];

    protected $guarded  = [];

    public function addresses()
    {
        return $this->hasMany('App\Models\EnterpriseAddress');
    }

    public function phones()
    {
        return $this->hasMany('App\Models\EnterprisePhone');
    }

    public function emails()
    {
        return $this->hasMany('App\Models\EnterpriseEmail');
    }

    public function certificates()
    {
        return $this->hasMany('App\Models\Certificate', 'enterprise_id', 'id');
    }

    public function additional()
    {
        return $this->hasOne('App\Models\EnterpriseAdditional', 'enterprise_id', 'id');
    }

    public function countrie()
    {
        return $this->hasOne('App\Models\Internationalization\Country', 'id', 'country_id');
    }

}
