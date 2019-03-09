<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'name' 
    ];
    protected $table = 'sectors';
    public function Stock()
    {
        return $this->hasMany(Stock::class);
    }
}
