<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Stock;
use App\Sector;
class stockmanager extends Controller
{
    public function show()
    {
        $a = Stock::with('Sector')->get();  
        return $a;
    }
    
    // public function add(Request $request)
    // {         
    //         $validator = Validator::make($request->all(), [
    //             'company_name' => 'required|string|max:255|unique:company_name',
    //             'exchange' => 'required',
    //             'sector'=> 'required',                  
    //             // '1_Year' => 'required',
    //             // '9_Month'=> 'required',
    //             // '6_Month' => 'required',
    //             // '3_Month'=> 'required',
    //             // '1_Month' => 'required',
    //             // '2_Week'=> 'required',
    //             // '1_Week' => 'required',
    //             'price'=> 'required'
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors());
    //     } 

    //     // $data = Stock::create([
    //     //     'company_name' => $request->get('company_name'),
    //     //     'exchange' => $request->get('exchange'), 
    //     //     'sector' => $request->get('sector'),
    //     //     'price' => $request->get('price'),  
    //     // ]);
         
    //     return Response::json('success',$data);
    // }
        

    public function add(Request $request)
    {      
        $validator = Validator::make($request->all(), [
                        'company_name' => 'required|string|max:255',
                        'exchange' => 'required',
                        'sector'=> 'required',                  
                        '1_Year' => 'required',
                        '9_Month'=> 'required',
                        '6_Month' => 'required',
                        '3_Month'=> 'required',
                        '1_Month' => 'required',
                        '2_Week'=> 'required',
                        '1_Week' => 'required',
                        'price'=> 'required'
                ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }
 
        $data = Stock::create([
                    'company_name' => $request->get('company_name'),
                    'exchange' => $request->get('exchange'), 
                    'sector' => $request->get('sector'),
                    '1_Year' => $request->get('1_Year'),
                    '9_Month'=> $request->get('9_Month'),
                    '6_Month' => $request->get('6_Month'),
                    '3_Month'=> $request->get('3_Month'),
                    '1_Month' => $request->get('1_Month'),
                    '2_Week'=> $request->get('2_Week'),
                    '1_Week' => $request->get('1_Week'),
                    'price' => $request->get('price'),  
                ]);
       
            return response()->json($data);
        
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
    
     
    public function getsector()
    {            
        $data = Sector::all('name');
        return response()->json($data);
    }

    public function sectorwisesotock(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'sector' => 'required' 
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $getsec = $request->get('sector');  
        $sector = Sector::where('name',$getsec)->get(['id']);
         
        $query2 = Stock::where('sector',$sector->toArray())->distinct()->get(['company_name','exchange','sector','1_Year','9_Month','6_Month','3_Month','1_Month','2_Week','1_Week','price']);       
        return response()->json($query2); 
    }

    public function findstock(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'search' => 'required',
            'exchange' => 'required'
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $search = $request->get('search'); 
        $exchange = $request->get('exchange'); 
 
        $query2 = Stock::where('exchange',$exchange)->where('company_name',$search)->distinct()->get(['company_name','exchange','sector','1_Year','9_Month','6_Month','3_Month','1_Month','2_Week','1_Week','price']);       

        if($query2 == '[]'){  
            return response()->json('No Stock Found !!');  
        }
        else{
            return response()->json($query2);
        } 
    }
}
