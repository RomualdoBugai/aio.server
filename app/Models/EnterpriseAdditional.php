<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseAdditional extends Model
{
    protected $table    = 'enterprise_additional';

    protected $fillable = [
        'enterprise_id',
        'municipal_registration',
        'estadual_registration',
        'encouraging_cultural',
        'tax_regime',
        'national_simple',
        'lot',
        'note',
        'operation_nature',
        'activity',
        'logo'
    ];

    protected $guarded  = [];

}
