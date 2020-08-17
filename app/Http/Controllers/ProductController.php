<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Company;
use Image;
use PDF;
use App\ProductImage;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index(Request $request){
        $product = new Product;
        $productImage = new ProductImage;
        $product->assignSearch($request);
        $indexData = $product->search();
        $size = 'x-small';
        foreach($indexData as $index=>$item){
            $logo= $indexData[$index]['thumbnail'];
            $indexData[$index]['thumbnail'] = $productImage->getImageSize($logo,$size);
        }
        return response()->json(array('status'=>'ok','indexData'=>$indexData));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), Product::validateRules());
        $upload=explode('M',ini_get('upload_max_filesize'));
        $uploadMaxSize = $upload[0]*1024;
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }  
        try {
            \DB::beginTransaction();
            $product = new Product;
            $productImage = new ProductImage;
            $product->assign($request);        
            $product->save();
            if($request->hasFile(0)){ 
                foreach($request->file() as $index=>$file){
                    $request->validate([
                        $index  => 'mimes:jpeg,jpg,png,gif|required|max:'.$uploadMaxSize,
                    ]);
                    $fileExtension = $file->extension();
                    $fileName = ($index).'.'.$fileExtension;
                    $year = date("Y");
                    $month = date("m");
                    $photoPath ='media/shop/product/gallery/'.$year.'/'.$month.'/'.$product->id;
                    $imagePath= $photoPath.'/'.$fileName;
                    $productImage=ProductImage::create(array('product_id'=>$product->id,'image'=>$imagePath));
                    $productImageGallery=ProductImage::where('product_id',$product->id)->get();
                    $imageGalleryId = $productImageGallery[$index]['id'];
                    $fileNameUpdate = ($imageGalleryId).'.'.$fileExtension;
                    $imagePathUpdate= $photoPath.'/'.$fileNameUpdate;
                    $pro = ProductImage::find($imageGalleryId);
                    $pro->image = $imagePathUpdate;
                    $pro->save();
                    $file->storeAs($photoPath,$fileNameUpdate);
                    $productImage->resizeImage($photoPath,$imageGalleryId,$fileExtension,$fileNameUpdate);
                }
            }
            \DB::commit();
            return response()->json(array('status'=>'ok','product'=>$product));
        
        } catch (Throwable $e) {
            \DB::rollback();
            return response()->json(array('status'=>'failed'));
        }
        
    }
    public function disable($id,Request $request){
        $product = Product::find($id);
        if ($product) {
            $product->status = 'Disabled';
            $product->save();
            return response()->json(['success' => 'success']);
        }
        return response()->json(['error' => 'error'], 422);
    }
    public function restore($id,Request $request){
        $product = Product::find($id);
        if ($product) {
            $product->status = 'Active';
            $product->save();
            return response()->json(['success' => 'success']);
        }
        return response()->json(['error' => 'error'], 422);
    }
    public function destroy($id,Request $request){
        $product = Product::find($id);
        ProductImage::where('product_id',$id)->delete();
        if($product){
            $destroy=Product::destroy($id);
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
    public function show($id,Request $request){
        $product = Product::find($id);
        $productImage=ProductImage::Where('product_id',$id)->get();
        foreach ($productImage as $index=>$item){
            $productImage[$index]['image']=url("storage/".$item->image);
        }
        return response()->json(array('status'=>'ok','product'=>$product,'productImage'=>$productImage ));    
    }
    public function update($id,Request $request)
    {
        try {
            \DB::beginTransaction();
            $upload=explode('M',ini_get('upload_max_filesize'));
            $uploadMaxSize = $upload[0]*1024;
            $validator = Validator::make($request->all(), Product::validateRules($id));
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }   
                $product = new Product;
                $productImage = new ProductImage;
                $product = Product::find($id);
                $productId = ProductImage::where('product_id',$id)->select('id')->get();
                $indexValue= count($productId);
                $product->assign($request);
                $product->save();
                if($request->hasFile(0)){ 
                    foreach($request->file() as $index=>$file){
                        $request->validate([
                            $index  => 'mimes:jpeg,jpg,png,gif|required|max:'.$uploadMaxSize,
                        ]);
                        $fileExtension = $file->extension();
                        $fileName = ($index).'.'.$fileExtension;
                        $year = date("Y");
                        $month = date("m");
                        $photoPath ='media/shop/product/gallery/'.$year.'/'.$month.'/'.$id;
                        $imagePath= $photoPath.'/'.$fileName;
                        ProductImage::create(array('product_id'=>$id,'image'=>$imagePath));
                        $productImageGallery=ProductImage::where('product_id',$id)->get();
                        $imageGalleryId = $productImageGallery[$index+$indexValue]['id'];
                        $fileNameUpdate = ($imageGalleryId).'.'.$fileExtension;
                        $imagePathUpdate= $photoPath.'/'.$fileNameUpdate;
                        $proUpdate = ProductImage::find($imageGalleryId);
                        $proUpdate->image = $imagePathUpdate;
                        $proUpdate->save();
                        $file->storeAs($photoPath,$fileNameUpdate);
                        $productImage->resizeImage($photoPath,$imageGalleryId,$fileExtension,$fileNameUpdate);
                    }
                } 
                \DB::commit();    
                return response()->json(array('status'=>'ok','product'=>$product));
        } catch (Throwable $e) {
            \DB::rollback();
            return response()->json(array('status'=>'failed'));
        }
    }
    public function viewImages($id){
        $viewImage = new ProductImage;
        $viewImageData = ProductImage::where('product_id',$id)->select('image')->get();
        $size = 'small';
        foreach($viewImageData as $index=>$item){
            $logo= $viewImageData[$index]['image'];
            $viewImageData[$index]['image'] = $viewImage->getImageSize($logo,$size);
        }
        return response()->json(array('status'=>'ok','viewImages'=>$viewImageData));
    }
    public function deleteImageItem(Request $request){
        $deleteImageItemRequest=$request->input();
        $deleteImageItem = $deleteImageItemRequest['image'];
        $delete = explode('storage/',$deleteImageItem);
        $productImage = new ProductImage;
        $productId = ProductImage::where('image',$delete[1])->select('product_id')->get();
        $productImage = ProductImage::where('image',$delete[1])->delete();
        $productGallery = ProductImage::where('product_id',$productId[0]['product_id'])->get();
        foreach($productGallery as $index=>$item){
            $productGallery[$index]['image'] = url('storage/'.$productGallery[$index]['image']);
        }
        return response()->json(array('status'=>'ok','productId'=>$productId[0]['product_id'],'gallery'=>$productGallery));
    }    
    public function showFront($id,Request $request){
        $product = Product::find($id);
        $user = $request->user('api');
        if($product && $product->status=="Active" && $user->customer->currentDate()<=$product->expiration_date){
            $productImage = new ProductImage;
            $size = "medium";
            $product->company;
            foreach ($product->gallery as $index=>$item){
                $product->gallery[$index]['thumbnail']=$productImage->getImageSize($item->image,"x-small");
                $product->gallery[$index]['image']=$productImage->getImageSize($item->image,$size);
            }
            $products = Product::whereStatus("Active")->where("expiration_date",">=",$user->customer->currentDate())->where('id','!=',$id)->inRandomOrder()->limit(6)->get();
            foreach($products as $index=>$item){
                if(isset($item->gallery[0]))$products[$index]['media_url'] = $productImage->getImageSize($item->gallery[0]->image,$size);
            }
            return response()->json(array('status'=>'ok','product'=>$product,'products'=>$products));    
        }else{
            return response()->json(array('status'=>'failed'), 422);    
        }
    }
    public function download($id,Request $request){
        $product = Product::find($id);
        $user = $request->user('api');
        if($product && $product->status=="Active" && $user->customer->currentDate()<=$product->expiration_date){
            $productImage = new ProductImage;
            $size = "small";
            $product->company->logo = $productImage->getImageSize($product->company->logo,$size);
            $voucherDate = date("Y-m-d",strtotime($user->customer->currentDate())+3600*24*7);
            $data = [
                'company'=>$product->company,
                'product'=>$product,
                'image'=>$productImage->getImageSize($product->gallery[0]->image,'medium'),
                'voucherDate'=>$voucherDate,
            ];
            $pdf = PDF::loadView('pdf.voucher', $data);
            $filename = "Fitemos ".$product->name;
            return $pdf->download($filename.'.pdf');
        }else{
            return response()->json(array('status'=>'failed'), 422);    
        }
    }
}
