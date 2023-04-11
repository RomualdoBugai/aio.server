<?php

namespace App\Models\FollowUp;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table    = 'follow_up';

    protected $fillable = [
        'description',
        'user_id'
    ];

    protected $guarded  = [];

    public function user()
    {
       return $this->hasOne('App\Models\User', 'id', 'user_id')->select(['id', 'name']);
    }

    public function attachments()
    {
        return $this->hasMany('App\Models\FollowUp\Attachment', 'follow_up_id', 'id');
    }

}
