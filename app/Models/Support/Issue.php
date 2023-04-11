<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
   protected $table     = "issue";

   protected $fillable  = ["name", "text", "user_id", "app_id", "issue_type_id", "issue_status_id"];

   protected $guarded   = [];

   public function status()
   {
      return $this->hasOne('App\Models\Support\IssueStatus', 'id', 'issue_status_id');
   }

   public function user()
   {
      return $this->hasOne('App\Models\User', 'id', 'user_id');
   }

   public function followUp()
   {
      return $this->hasOne('App\Models\Support\IssueFollowUp', 'id', 'issue_id');
   }

}
