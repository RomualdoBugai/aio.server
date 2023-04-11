<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterprisePerson extends Model
{
    protected $table = 'enterprise_person';

    protected $fillable = [
    	'enterprise_id',
    	'name',
        'description'
    ];

    protected $guarderd = [];

    public function enterprise()
    {
        return $this->hasOne('App\Models\Enterprise', 'id', 'enterprise_id');
    }

}
