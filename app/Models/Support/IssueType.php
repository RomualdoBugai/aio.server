<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;

class IssueType extends Model
{
   protected $table     = "issue_type";

   protected $fillable  = ["name"];

   protected $guarded   = [];

}
