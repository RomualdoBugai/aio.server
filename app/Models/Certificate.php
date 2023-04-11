<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table    = 'certificate';

    protected $fillable = [
        'enterprise_id',
        'password',
        'name',
        'pfx_file',
        'crt_file',
        'valid_from',
        'valid_to',
        'hash',
        'data'
    ];

    protected $guarded  = [];

    public function enterprise()
    {
        return $this->hasOne('App\Models\Enteprise');
    }

}
