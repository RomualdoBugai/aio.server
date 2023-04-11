<?php

namespace App\Models\FollowUp;

use Illuminate\Database\Eloquent\Model;

class FollowUpReason extends Model
{
    protected $table    = 'follow_up_reason';

    protected $fillable = [
        'name'
    ];

    protected $guarded  = [];

}
