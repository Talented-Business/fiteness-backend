<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Category;

class CategoryController extends Controller
{
    public function all(){
        $categories = Category::all();
        return response()->json($categories);
    }
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function index(Request $request)
    {
        $category = new Category;
        $category->assignSearch($request);
        return response()->json($category->search());
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Category::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $category = new Category;
        $category->assign($request);
        $category->save();
        return response()->json(array('status'=>'ok','category'=>$category));
    }
    public function update($id,Request $request)
    {
        $validator = Validator::make($request->all(), Category::validateRules());
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        $category = Category::find($id);
        $category->assign($request);
        $category->save();
        return response()->json(array('status'=>'ok','category'=>$category));
    }
    public function updateStatus($id){
        $category = Category::find($id);
        $category->save();
        return response()->json($category);
    }
    public function destroy($id){
      $asset = Category::find($id);
      if($asset){
          $destroy=Category::destroy($id);
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
    public function show($id){
        $category = Category::find($id);
        return response()->json($category);
    }    
}