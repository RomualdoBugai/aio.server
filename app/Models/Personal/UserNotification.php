<?php

namespace App\Models\Personal;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{

    protected $table    = 'user_notification';

    protected $fillable = ['user_id', 'notification'];

    protected $guarded  = ['id', 'created_at', 'updated_at'];

}
