<?php

namespace App\Models\Financial;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
   protected $table     = "expense";

   protected $fillable  = [
      'name',
      'description',
      'amount',
      'due_date_at',
      'is_active',
      'currency_id',
      'user_id',
      'bank_account_id',
      'is_closed'
   ];

   protected $guarded   = [];

   public function bankAccount()
   {
      return $this->hasOne('App\Models\BankAccount', 'id', 'bank_account_id');
   }

   public function currency()
   {
      return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
   }

   public function user()
   {
      return $this->hasOne('App\Models\User', 'id', 'user_id');
   }

}
