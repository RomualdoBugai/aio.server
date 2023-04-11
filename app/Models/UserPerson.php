<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPerson extends Model
{
    protected $table    = 'user_person';

    protected $fillable = ['user_id', 'person_id'];

    protected $guarded  = [];

}
