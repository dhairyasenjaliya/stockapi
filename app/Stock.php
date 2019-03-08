<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = [
        'company_name',
        'exchange',
        'sector',
        'price',
        '1_Year',
        '9_Month',
        '6_Month',
        '3_Month',
        '1_Month',
        '2_Week',
        '1_Week',
        'price' 
    ];
    public function Sector()
    {
        return $this->belongsTo(Sector::class ,'id');
    }
}
