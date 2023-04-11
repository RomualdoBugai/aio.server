<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $table    = 'user_settings';

    protected $fillable = [
        "user_id",
        "date_format",
        "input_date_format",
        "timezone",
        "country_id"
    ];

    protected $guarded  = [];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function country()
    {
        return $this->hasOne('App\Models\Internationalization\Country', 'id', 'country_id');
    }

}
