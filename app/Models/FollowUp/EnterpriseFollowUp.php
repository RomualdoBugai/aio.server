<?php

namespace App\Models\FollowUp;

use Illuminate\Database\Eloquent\Model;

class EnterpriseFollowUp extends Model
{
    protected $table    = 'enterprise_follow_up';

    protected $fillable = [
        'enterprise_id',
        'follow_up_id'
    ];

    protected $guarded  = [];

    public function followUp()
    {
       return $this->hasOne('App\Models\FollowUp\FollowUp', 'id', 'follow_up_id');
    }

    public function enterprise()
    {
        return $this->hasOne('App\Models\Enterprise', 'id', 'enterprise_id');
    }

    public function attachment()
    {
        return $this->hasMany('App\Models\FollowUp\Attachment', 'follow_up_id', 'follow_up_id');
    }

}
