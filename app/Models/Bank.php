<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
   protected $table 	= "bank";

   protected $fillable 	= ["id", "name", "code"];

   protected $guarded 	= [];

   public function accounts()
   {
      return $this->hasMany('App\Models\BankAccount', 'bank_id', 'id');
   }

}
