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
        $shortcode->assign($request);        
        $shortcode->save();
        return response()->json(array('status'=>'ok','shortcode'=>$shortcode));
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Shortcode::validateRules($id));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $shortcode = Shortcode::find($id);
        $shortcode->assign($request);
        $shortcode->save();
        return response()->json(array('status'=>'ok','shortcode'=>$shortcode));
    }
    public function show($id){
        $shortcode = Shortcode::find($id);
        if($shortcode->mail==null){
            $shortcode->mail = "";
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

}
