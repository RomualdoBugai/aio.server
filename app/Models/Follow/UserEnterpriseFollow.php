<?php

namespace App\Models\Follow;

use Illuminate\Database\Eloquent\Model;

class UserEnterpriseFollow extends Model
{
    protected $table    = 'user_enterprise_follow';

    protected $fillable = [
        'enterprise_id',
        'user_id'
    ];

    protected $guarded  = [];

    public function user()
    {
       return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function enterprise()
    {
        return $this->hasOne('App\Models\Enterprise', 'id', 'enterprise_id');
    }

    public static function check($enterprise_id, $user_id)
    {
        $check = self::where('enterprise_id', $enterprise_id)
                ->where('user_id', $user_id)
                ->first();
        return ( $check != null ? true : false );
    }

}
