<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    public function Sector()
    {
        return $this->belongsTo(Sector::class ,'id');
    }
}
