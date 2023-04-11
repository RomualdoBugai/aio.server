<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table     = "customer";

    protected $filltable = ['person_id', 'enterprise_id', 'table'];

    protected $guarded   = [];

}
