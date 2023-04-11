<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyQuote extends Model
{
    protected $table        = "currency_quote";

    protected $filltable    = [
    	"currency_id", 
    	"rate", 
    	"day"
    ];

    protected $guarded      = [];

    public $timestamps      = true;

    public function currency()
	{
	    return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
	}

}
