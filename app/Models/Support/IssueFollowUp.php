<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;

class IssueFollowUp extends Model
{
   protected $table     = "issue_follow_up";

   protected $fillable  = ["description", "user_id", "issue_id"];

   protected $guarded   = [];

   public function issue()
   {
      return $this->hasOne('App\Models\Support\Issue', 'id', 'issue_id');
   }

   public function user()
   {
      return $this->hasOne('App\Models\User', 'id', 'user_id');
   }

}
