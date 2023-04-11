<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $table    = 'blocked_user';

    protected $fillable = ['id', 'user_id', 'app_id'];

    protected $guarded  = [];

    public static function isBlocked(\App\Models\User $user)
    {
        $in = self::where('user_id', $user->id)->first();
        return (bool) !( $in == null ? false : true );
    }
}
