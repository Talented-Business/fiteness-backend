<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyCountry extends Model
{
    protected $table='company_countries';
    protected $fillable = ['company_id','country']; 
}
