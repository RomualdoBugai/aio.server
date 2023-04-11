<?php

namespace App\Models\FollowUp;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $table    = 'attachment';

    protected $fillable = [
        'follow_up_id',
        'name',
        'filename',
        'size',
        'format',
        'path'
    ];

    protected $guarded  = [];

    public function followUp()
    {
       return $this->hasOne('App\Models\FollowUp\FollowUp', 'id', 'follow_up_id');
    }

}
