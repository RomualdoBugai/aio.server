<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseEmail extends Model
{
    protected $table    = 'enterprise_email';

    protected $fillable = ['enterprise_id', 'email', 'is_active'];

    protected $guarderd = [];

    public static function check($email = null)
    {
        $check = self::where('email', $email)
        ->first();
        return ( $check == null ? true : false );
    }
    
}
