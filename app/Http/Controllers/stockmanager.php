<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Stock;
use App\Sector;
class stockmanager extends Controller
{
    public function show(){         
        $a = Stock::with('Sector')->get();  
        return $a;
    }


    public function find(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'search' => 'required'          
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $search = $request->get('search'); 

        $query2 = Stock::where('company_name',$search)->distinct()->get(['company_name','exchange','sector','1_Year','9_Month','6_Month','3_Month','1_Month','2_Week','1_Week','price']);       

        if($query2 == '[]'){  
            return response()->json('No Stock Found !!');  
        }
        else{
            return response()->json($query2);
        } 
    }
    
}
