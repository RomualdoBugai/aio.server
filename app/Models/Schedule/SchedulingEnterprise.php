<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

class SchedulingEnterprise extends Model
{
    protected $table    = 'scheduling_enterprise';

    protected $fillable = [
        'scheduling_id',
        'enterprise_id',
        'is_active',
    ];

    protected $guarded  = [];

    public function scheduling()
    {
        return $this->belongsTo('App\Models\Schedule\Scheduling', 'scheduling_id', 'id');
    }

    public function enterprise()
    {
        return $this->hasOne('App\Models\Enterprise', 'id', 'enterprise_id')->select(['id', 'name']);
    }

}
