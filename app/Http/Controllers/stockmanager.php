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
        $data = json_encode($request->data);  
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
            return response()->json($query2);  
        }
        else{
            return response()->json($query2);
        }  
    }
     
    public function getsector()
    {
        $data = Sector::orderBy('name')->select('id','name')->get(); 
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


    public function bestreturnstock(Request $request)
    {     
        $validator = Validator::make($request->all(), [
            'stock_id' => 'required',
            'time_frame'=>'required' ,
            'exchange' => 'required'
        ]); 
         
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }   
         
        $findsector = Stock::where('id',$request->get('stock_id'))->get();
        foreach($findsector as $sec){
                $sector = $sec->sector;
                $name = $sec->company_name; 
        }

        $bridge =  explode('_',$request->get('time_frame')) ;
        $chk_price = $bridge[0].'_'.$bridge[1] .'_Price';

        //order by change !

        $lessthanfifty = Stock::where('company_name', '!=',  $name )->where('exchange',$request->get('exchange'))->where('price','<','51')->orderBy($request->get('time_frame') ,'desc')->take(30)->get(['id','company_name','exchange','sector',$request->get('time_frame'),$chk_price]);

        $fiftytohundred = Stock::where('company_name', '!=',  $name )->where('exchange',$request->get('exchange'))->where('price','>=','51')->where('price','<=','100')->orderBy($request->get('time_frame') ,'desc')->take(30)->get(['id','company_name','exchange','sector',$request->get('time_frame'),$chk_price]);

        $hundredtofivehundred = Stock::where('company_name', '!=',  $name )->where('exchange',$request->get('exchange'))->where('price','>=','101')->where('price','<=','500')->orderBy($request->get('time_frame') ,'desc')->take(30)->get(['id','company_name','exchange','sector',$request->get('time_frame'),$chk_price]); 

        $grtthanfivehundred = Stock::where('company_name', '!=',  $name )->where('exchange',$request->get('exchange'))->where('price','>=','500')->orderBy($request->get('time_frame') ,'desc')->take(30)->get(['id','company_name','exchange','sector',$request->get('time_frame'),$chk_price]);
        
        return response()->json(['lessthanfifty'=>json_decode($lessthanfifty), 'fiftytohundred'=>json_decode($fiftytohundred) ,'hundredtofivehundred'=> json_decode($hundredtofivehundred), 'grtthanfivehundred'=>json_decode($grtthanfivehundred)]);
         
    }  
    public function sectorwisesotock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sector_id' => 'required',
            'time_frame'=>'required',
            'exchange'=>'required' ,
        ]);
        //Flag for low return high return 

        //Exchage
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        } 
        
        $bridge =  explode('_',$request->get('time_frame')) ;
        $chk_price = $bridge[0].'_'.$bridge[1] .'_Price'; 

        $getsec = $request->get('sector_id');  
        $sector = Sector::where('id',$getsec)->get(['id']);
        
        $time_frame = $request->get('time_frame');
        $flag = $request->get('flag');  
 
        if($flag == true){ 
            $query2 = Stock::where('sector',$sector->toArray())->where('exchange',$request->get('exchange'))->where($time_frame , '>' , '0')->orderBy($time_frame,'desc')->get(['id','company_name','exchange','sector',$request->get('time_frame'),$chk_price]);
            return response()->json($query2); 
        }
        if($flag == false){ 
            $query2 = Stock::where('sector',$sector->toArray())->where('exchange',$request->get('exchange'))->where($time_frame , '<' , '0')->orderBy($time_frame,'asc')->get(['id','company_name','exchange','sector',$request->get('time_frame'),$chk_price]); 
             return response()->json($query2);
        }
    }

    public function findallstock(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'searchstring' => 'required', 
        ]);
 
        if($validator->fails()) {
            return response()->json([ 'error'=> $validator->messages()], 401);
        }

        $search = $request->get('searchstring'); 
 
        $query2 =  Stock::where('company_name', 'like', '%'.$search.'%')->distinct()->get();

        if($request->get('exchange')){
            $query2 =  Stock::where('exchange',$request->get('exchange'))->where('company_name', 'like', '%'.$search.'%')->distinct()->get();
        }

        if($query2 == '[]'){  
            return response()->json($query2);  
        }
        else{
            return response()->json($query2);
        } 
    } 

    public function removeallstock()
    {
        DB::table('stocks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sectors')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return 'Stocks & Sectors Cleared';
    }

    public function searchstock(Request $request)
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
        $query2 = Stock::where('exchange','like', '%'.$exchange.'%' )->where('company_name', 'like', '%'.$search.'%')->distinct()->get();          

        if($query2 == '[]'){  
            return response()->json($query2);  
        }
        else{
            return response()->json($query2);
        } 
    } 
}
