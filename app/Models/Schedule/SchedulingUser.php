<?php

namespace App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

class SchedulingUser extends Model
{
    protected $table    = 'scheduling_user';

    protected $fillable = [
        'scheduling_id',
        'user_id',
        'is_active',
    ];

    protected $guarded  = [];

    public function scheduling()
    {
        return $this->belongsTo('App\Models\Schedule\Scheduling', 'scheduling_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select(['id', 'name', 'email']);
    }

    public static function check($scheduling_id, $user_id)
    {
        $check = self::where('scheduling_id', $scheduling_id)
                ->where('user_id', $user_id)
                ->first();
        return ( $check != null ? true : false );
    }

}
