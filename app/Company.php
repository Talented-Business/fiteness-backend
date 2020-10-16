<?php

namespace App;
use Intervention\Image\Facades\Image;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\DB;
class Company extends Model
{
    protected $fillable = ['name','description','phone','mail','is_all_countries','mobile_phone','website_url','address','facebook','instagram','twitter','horario'];    
    private $pageSize;
    private $pageNumber;
    private $search;
    private $countryCode;
    private $expirationDate;
    private static $searchableColumns = ['search'];
    public static function validateRules($id=null){
        $upload=explode('M',ini_get('upload_max_filesize'));
        $uploadMaxSize = $upload[0]*1024;
        return array(
            'name'=>'required|max:255|unique:companies,name,'.$id,
            'description'=>'required',
            'mail'=>'required|email|unique:companies,mail,'.$id,
            'phone' => 'required|numeric',
            'logo' => 'mimes:jpeg,jpg,png,gif|max:'.$uploadMaxSize,
        );
    }
    public function countries(){
        return $this->hasMany("App\CompanyCountry");
    }
    public function getImageSize($logo,$size) 
    {
        $image =  implode('-' . Setting::IMAGE_SIZES[$size] . '.', explode('.', $logo));
        $imagePath = url('storage/'.$image);
        return $imagePath;
    }
    public function products(){
        return $this->hasMany('App\Product');
    }
    
    public function resizeImage($photoPath,$fileExtension)
    {   
        $sizes = Setting::convertSizes();
        for($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];
            $resizeImg = Image::make('storage/' . $photoPath .'/'. $this->id . '.' . $fileExtension);
            $height = $resizeImg->height();
            $width = $resizeImg->width();
            if($width > $height) {
                $cropStartPointX = round(($width - $height) / 2);
                $cropStartPointY = 0;
                $cropWidth = $height;
                $cropHeight = $height;
            }
            else {
                $cropStartPointX = 0;
                $cropStartPointY = round(($height - $width) / 2);
                $cropWidth = $width;
                $cropHeight = $width;
            }
            $resizeImg->crop($cropWidth, $cropHeight, $cropStartPointX, $cropStartPointY)
            ->resize($size[0], $size[1], function($constraint) {
                $constraint->aspectRatio();
            });
            $resizeImg->save('storage/' . $photoPath .'/'. $this->id . '-' . $size[0] . 'X' . $size[1] . '.' . $fileExtension);
        }
        return $sizes;
    }
    public function search(){
        $where = Company::where(function($query){
            if($this->search!=null){
                $query->Where('name','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        return $response;
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function assignFrontSearch($request){
        $user = $request->user('api');
        $this->countryCode = $user->customer->country_code;
        $this->expirationDate = $user->customer->currentDate();
        $this->search = null;
        $this->status = 'Publish';
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public function frontSearch(){
        // DB::enableQueryLog();
        $where = Company::whereHas('countries',function($query){
            $query->where('country','=',strtoupper($this->countryCode));
            })
            ->where('is_all_countries','=','no')
            ->orWhere('is_all_countries','=','yes')
            ->whereHas('products',function($query){
                $query->where('status', '=', "Active")
                    ->where('expiration_date', '>=', $this->expirationDate);
            });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        $items = $response->items();
        foreach ($items as $index => $item){
            //print_r($item->logo);die;
            $items[$index]->logo = url("storage/".$item->logo);
        }
        // dd(DB::getQueryLog());
        return $response;
    }
}
