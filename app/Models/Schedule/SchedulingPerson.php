<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

class SchedulingPerson extends Model
{
    protected $table    = 'scheduling_person';

    protected $fillable = [
        'scheduling_id',
        'person_id',
        'is_active',
    ];

    protected $guarded  = [];

    public function scheduling()
    {
        return $this->belongsTo('App\Models\Schedule\Scheduling', 'scheduling_id', 'id');
    }

    public function person()
    {
        return $this->hasOne('App\Models\Person', 'id', 'person_id')->select(['id', 'name']);
    }

}
