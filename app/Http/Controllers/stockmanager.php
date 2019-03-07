<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Sector;
class stockmanager extends Controller
{
    public function show(){         
        $a = Stock::with('Sector')->get();  
        return $a;
    }
}
