<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medal;
use Illuminate\Support\Facades\Validator;
use App\MagicImage;

class MedalController extends Controller
{
    use MagicImage;
    public function __construct()
    {
        $this->middleware(['permission:medals']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Medal::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $medal = new Medal;
        if($request->hasFile('image')&&$request->file('image')->isValid()){ 
            $photoPath = $request->image->store('media/medal');
            /*$file = storage_path('app/public/'.$photoPath);
            $this->cropImage($file,50,$file);
            if(PHP_OS == 'Linux'){
                $output = shell_exec("mogrify -auto-orient $file");
                sleep(1);
            }*/
            $medal->image = $photoPath;
        }        
        $medal->assign($request);        
        $medal->save();
        return response()->json(array('status'=>'ok','medal'=>$medal));
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Medal::validateRules($id));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()),403);
        }
        $medal = Medal::find($id);
        if($request->hasFile('image')&&$request->file('image')->isValid()){ 
            $photoPath = $request->image->store('media/medal');
            /*$file = storage_path('app/public/'.$photoPath);
            $this->cropImage($file,50,$file);
            if(PHP_OS == 'Linux'){
                $output = shell_exec("mogrify -auto-orient $file");
                sleep(1);
            }*/
            $medal->image = $photoPath;
        }        
        $medal->assign($request);
        $medal->save();
        return response()->json(array('status'=>'ok','medal'=>$medal));
    }
    public function show($id){
        $medal = Medal::find($id);
        $medal->image = url('storage/'.$medal->image);      
        return response()->json($medal);
    }
    public function destroy($id){
        $medal = Medal::find($id);
        if($medal){
            $destroy=Medal::destroy($id);
        }
        if ($destroy){
            $data=[
                'status'=>'1',
                'msg'=>'success'
            ];
        }else{
            $data=[
                'status'=>'0',
                'msg'=>'fail'
            ];
        }        
        return response()->json($data);
    }
    public function index(Request $request){
        $medals = Medal::orderBy('count')->get();
        $total =0;
        if($medals){
            $total = count($medals);
            foreach ($medals as $key => $medal) {
                $medal->image = url('storage/'.$medal->image);            
            }
        }
        return response()->json(['data'=>$medals,'total'=>$total]);
    }
}