<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonEmail extends Model
{
    protected $table = 'person_email';

    protected $fillable = [
    	'person_id',
    	'email',
    	'is_active'
    ];

    protected $guarderd = [];

    public static function check($email = null)
    {
        $check = self::where('email', $email)
        ->first();
        return ( $check == null ? true : false );
    }


}
