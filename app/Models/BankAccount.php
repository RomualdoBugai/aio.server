<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
   protected $table     = "bank_account";

   protected $fillable  = [
      'bank_id', 'name',
      'agency_number', 'agency_number_digit',
      'account_number', 'account_number_digit',
      'is_savings_account', 'is_current_account',
      'opening_balance', 'opening_at',
      'is_active'
   ];

   protected $guarded   = [];

   public function bank()
   {
      return $this->hasOne('App\Models\Bank', 'id', 'bank_id');
   }

}
