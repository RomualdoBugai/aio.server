<?php

namespace App\Models\Internationalization;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table    = 'country';

    protected $fillable = ['id', 'code', 'name', 'international_code'];

    protected $guarded  = [];

}
