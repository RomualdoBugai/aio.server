<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class EmailBlacklist extends Model
{
   protected $table     = "email_blacklist";

   protected $fillable  = [
      "email", "app_id"
   ];

   protected $guarded   = [

   ];

}
