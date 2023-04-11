<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseBranch extends Model
{
    protected $table    = 'enterprise_branch';

    protected $fillable = [
        'matrix_enterprise_id',
        'branch_enterprise_id'
    ];

    protected $guarded  = [];

}
