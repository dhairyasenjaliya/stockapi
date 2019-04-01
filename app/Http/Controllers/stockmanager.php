<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Stock;
use App\Sector;
use DB;

class stockmanager extends Controller
{
    public function show()
    {
        $a = Stock::with('Sector')->get();  
        return $a;
    }
  
    public function add(Request $request)
    {  
        $data = json_encode($request->data ) ;

        $val = json_decode($data,true);

        Stock::insert($val);

        return response()->json(count($val).'--Stock Added');  
    }

    public function addsector(Request $request)
    {      
        $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:255' 
                ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }
 
        $data = Sector::create([
                    'name' => $request->get('name')   
                ]);
        return response()->json($data->id);
        
    }
    
    public function findsector(Request $request)
    {            
        $validator = Validator::make($request->all(), [
            'searchstring' => 'required' 
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $search = $request->get('searchstring');  
 
        $query2 = Sector::where('name',$search)->get(); 

        if($query2 == '[]'){  
            return response()->json('No Sector Exists!!');  
        }
        else{
            return response()->json($query2);
        } 
    
    }
     
    public function getsector()
    {            
        $data = Sector::all('id','name');
        return response()->json($data);
    }


    public function all()
    {            
        $data = Stock::all(); //paginate(10)
        return response()->json($data);  
    }

    public function fav(Request $request)
    {    
        $validator = Validator::make($request->all(), [
            'stock' => 'required' 
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }
        
        $stock = $request->stock;
        $fav_counter = Stock::where('company_name',$stock)->get('fav_counter')->toArray() ;     

        $data = Stock::where('company_name',$stock)->update(['fav_counter' => DB::raw('fav_counter + 1')  ]);
        return response()->json('liked'); 
    } 

    public function sectorwisesotock(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'sector_id' => 'required' 
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $getsec = $request->get('sector_id');  
        $sector = Sector::where('id',$getsec)->get(['id']);
         
        $query2 = Stock::where('sector',$sector->toArray())->distinct()->get();  
        return response()->json($query2); 
    }

    public function findstock(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'searchstring' => 'required',
            'exchange' => 'required'
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $search = $request->get('searchstring'); 
        $exchange = $request->get('exchange'); 
 
        $query2 = Stock::where('exchange',$exchange)->where('company_name',$search)->distinct()->get(['company_name','exchange','sector','1_Year','9_Month','6_Month','3_Month','1_Month','2_Week','1_Week','price']);       

        if($query2 == '[]'){  
            return response()->json('No Stock Found !!');  
        }
        else{
            return response()->json($query2);
        } 
    }


    public function searchstock(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'searchstring' => 'required'  
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $search = $request->get('searchstring');  
 
        $query2 = Stock::where('company_name', 'like', '%'.$search.'%')->get('company_name');       

        if($query2 == '[]'){  
            return response()->json('No Stock Found !!');  
        }
        else{
            return response()->json($query2);
        } 
    }
}
