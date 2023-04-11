<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table    = 'promotion';

    protected $fillable = ['name', 'description', 'days', 'code', 'user_id', 'approved', 'is_active'];

    protected $guarded  = [];
}
