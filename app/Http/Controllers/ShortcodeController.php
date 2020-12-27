<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shortcode;
use Illuminate\Support\Facades\Validator;

class ShortcodeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:shortcodes']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Shortcode::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $shortcode = new Shortcode;
        $shortcode->fill($request->all());
        $shortcode->save();
        if($request->hasFile('video')&&$request->file('video')->isValid()){ 
            $shortcode->uploadVideo($request->file('video'));
            $shortcode->save();
        }        
        return response()->json(array('status'=>'ok','shortcode'=>$shortcode));
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Shortcode::validateRules($id));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $shortcode = Shortcode::find($id);
        $shortcode->fill($request->all());
        if($request->hasFile('video')&&$request->file('video')->isValid()){
            $shortcode->uploadVideo($request->file('video'));
        }
        $shortcode->save();
        return response()->json(array('status'=>'ok','shortcode'=>$shortcode));
    }
    public function show($id){
        $shortcode = Shortcode::find($id);
        if($shortcode && $shortcode->mail==null){
            $shortcode->mail = "";
        }
        if($shortcode && $shortcode->alternate_a==null){
            $shortcode->alternate_a = "";
        }
        if($shortcode && $shortcode->alternate_b==null){
            $shortcode->alternate_b = "";
        }
        if($shortcode && $shortcode->level==null){
            $shortcode->level = "";
        }
        if($shortcode && $shortcode->multipler_a==null){
            $shortcode->multipler_a = "";
        }
        if($shortcode && $shortcode->multipler_b==null){
            $shortcode->multipler_b = "";
        }
        if($shortcode && $shortcode->time==null){
            $shortcode->time = "";
        }
        if($shortcode && $shortcode->instruction==null){
            $shortcode->instruction = "";
        }
        return response()->json($shortcode);
    }
    public function destroy($id){
        $shortcode = Shortcode::find($id);
        if($shortcode){
            $destroy=Shortcode::destroy($id);
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
        $shortcode = new Shortcode;
        $shortcode->assignSearch($request);
        return response()->json($shortcode->search());
    }
    public function disable($id){
        $shortcode = Shortcode::find($id);
        $shortcode->status = "Inactive";
        $shortcode->save();
        return response()->json($shortcode);
    }
    public function active($id){
        $shortcode = Shortcode::find($id);
        $shortcode->status = "Active";
        $shortcode->save();
        return response()->json($shortcode);
    }
    public function list(){
        $shortcodes = Shortcode::whereStatus('Active')->get();
        return response()->json($shortcodes);
    }
    public function getSize(){
        $shortcode = new Shortcode;
        return response()->json($shortcode->getMaximumFileUploadSize());
    }
}
