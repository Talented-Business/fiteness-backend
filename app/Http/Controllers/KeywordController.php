<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Keyword;
use Illuminate\Support\Facades\Validator;
/**
 * @group Keyword   
 *
 * APIs for managing  keyword
 */

class KeywordController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:shortcodes']);
    }
    /**
     * create a keyword.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Keyword::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $keyword = new Keyword;
        $keyword->fill($request->all());
        $keyword->save();
        return response()->json(array('status'=>'ok','keyword'=>$keyword));
    }
    /**
     * update a keyword.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Keyword::validateRules($id));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $keyword = Keyword::find($id);
        $keyword->fill($request->all());
        $keyword->save();
        return response()->json(array('status'=>'ok','keyword'=>$keyword));
    }
    /**
     * show a keyword.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id){
        $keyword = Keyword::find($id);
        return response()->json($keyword);
    }
    /**
     * delete a keyword.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id){
        $keyword = Keyword::find($id);
        if($keyword){
            $destroy=Keyword::destroy($id);
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
    /**
     * search keywords.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $keyword = new Keyword;
        $keyword->assignSearch($request);
        return response()->json($keyword->search());
    }
    /**
     * get all keywords.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function list(){
        $keywords = Keyword::all();
        return response()->json($keywords);
    }
}