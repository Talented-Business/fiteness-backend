<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Country;
use App\CompanyCountry;
use App\Product;
use App\ProductImage;
use Illuminate\Support\Facades\Validator;
/**
 * @group Company
 *
 * APIs for managing  companies
 */

class CompanyController extends Controller
{
    /**
     * search shops(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('shops')){
            $company = new Company;
            $company->assignSearch($request);
            $indexData = $company->search();
            $size = 'x-small';
            foreach($indexData as $index=>$item){
                $logo= $indexData[$index]['logo'];
                $indexData[$index]['logo'] = $company->getImageSize($logo,$size);
            }
            return response()->json($indexData);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * create a shop(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request){ 
        $user = $request->user('api');
        if($user->can('shops')){
            $validator = Validator::make($request->all(), Company::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
            }
            try {
                \DB::beginTransaction();
                $company = new Company;
                $countryArr = explode(',', $request->allCountries);
                $year = date("Y");
                $month = date("m");
                $company->assign($request);  
                $company->save();
                if($request->hasFile('logo')&&$request->file('logo')->isValid()){ 
                    $fileExtension = $request->file('logo')->extension();
                    $fileName = $company->id.'.'.$fileExtension;
                    $photoPath = 'media/shop/company/'.$year.'/'.$month.'/'.$company->id;
                    $request->file('logo')->storeAs($photoPath,$fileName);
                    $company->logo = $photoPath.'/'.$fileName;
                    $company->save();
                    $company->resizeImage($photoPath,$fileExtension);
                }  
                if($request->hasFile('post_image')&&$request->file('post_image')->isValid()){
                    $company->uploadMedia($request->post_image);
                    $company->save();
                }    
                if(!empty($countryArr[0])){
                    for ($i = 0; $i<count($countryArr); $i++){
                        $resultShortName=Country::where('long_name',$countryArr[$i])->Select('short_name')->get();
                        foreach($resultShortName as $short)
                        {
                            $shortName[$i] = $short->short_name;
                        }
                        CompanyCountry::create(array('company_id'=>$company->id,'country'=>$shortName[$i]));
                    }
                }
                \DB::commit();    
                return response()->json(array('status'=>'ok','company'=>$company));
            } catch (Throwable $e) {
                \DB::rollback();
                return response()->json(array('status'=>'failed'));
            }
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * get all countries.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function fetchCountries(Request $request){
        return Country::all();
    }
    /**
     * disable a shop(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function disable($id,Request $request){
        $user = $request->user('api');
        if($user->can('shops')){
            $company = Company::find($id);
            if ($company) {
                $company->status = 'Disable';
                $company->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * restore a shop(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function restore($id,Request $request){
        $user = $request->user('api');
        if($user->can('shops')){
            $company = Company::find($id);
            if ($company) {
                $company->status = 'Active';
                $company->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * delete a shop(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id,Request $request){
        $user = $request->user('api');
        if($user->can('shops')){
            $company = Company::find($id);
            $photoPath = $company->logo;
            
            CompanyCountry::where('company_id',$id)->delete();
            if($company){
                if(\Storage::exists($photoPath)){
                    \Storage::delete($photoPath);
                }
                $destroy=Company::destroy($id);
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
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * update a shop(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('shops')){
            try {
                $validator = Validator::make($request->all(), Company::validateRules($id));
                    if ($validator->fails()) {
                        return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
                }
                \DB::beginTransaction();
                $companyCountry = new CompanyCountry;
                $company = new Company;
                $country = new Country;
                $company = Company::find($id);
                CompanyCountry::where('company_id',$id)->delete();
                $countryArr = explode(',', $request->allCountries);
                $year = date("Y");
                $month = date("m");
                if($request->hasFile('post_image')&&$request->file('post_image')->isValid()){
                    $company->uploadMedia($request->post_image);
                }    
                if($request->hasFile('logo')&&$request->file('logo')->isValid()){ 
                    $fileExtension = $request->file('logo')->extension();
                    $fileName = $company->id.'.'.$fileExtension;
                    $photoPath = 'media/shop/company/'.$year.'/'.$month.'/'.$company->id;
                    $request->file('logo')->storeAs($photoPath,$fileName);
                    $company->logo = $photoPath.'/'.$fileName;
                    $company->save();
                    $company->resizeImage($photoPath,$fileExtension);
                }
                $company->assign($request);
                $company->save();
                if(!empty($countryArr[0])){
                    for ($i = 0; $i<count($countryArr); $i++){
                        $resultShortName=Country::where('long_name',$countryArr[$i])->select('short_name')->get();
                        foreach($resultShortName as $short)
                            {
                                $shortName[$i] = $short->short_name;
                            }
                        CompanyCountry::create(array('company_id'=>$company->id,'country'=>$shortName[$i]));
                    }
                }
                \DB::commit();    
                return response()->json(array('status'=>'ok','company'=>$company));
            } catch (Throwable $e) {
                \DB::rollback();
                return response()->json(array('status'=>'failed'));
            }
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * show a shop(company).
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function show($id,Request $request){
        $user = $request->user('api');
        if($user->can('shops')){
            $company = Company::find($id);
            $size = 'small';
            $logo = $company['logo'];
            $company['logo'] = secure_url('storage/'.$logo);
            $company['image'] = $company->getImageSize($logo,$size);
            if($company->post_image_id){
                $media = \App\Models\Media::find($company->post_image_id);
                $company['post_image'] = $media->url;
            }else{
                $company['post_image'] = "";
            }
            $nullAttributes = ["mobile_phone","website_url","horario","facebook","instagram","twitter"];
            foreach($nullAttributes as $attribute){
                if($company->{$attribute} == null){
                    $company->{$attribute} = "";
                }
            }
            if($company->is_all_countries=="no"){
                $companyCountries=CompanyCountry::where('company_id',$id)->select("country")->get(); 
                $countryLongNames = [];
                foreach($companyCountries as $index=>$country){
                    $countryObject=Country::where('short_name',$country->country)->first();
                    $countryLongNames[$index] = $countryObject->long_name;    
                }  
                return response()->json(array('status'=>'ok','company'=>$company,'country'=>$countryLongNames));
            }
            return response()->json(array('status'=>'ok','company'=>$company));    
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * get a shop(company) on frontend.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function view($id,Request $request){
        $company = Company::find($id);
        $user = $request->user('api');
        if($user && $user->customer)\App\Jobs\Activity::dispatch($user->customer);
        if($company->status == "Active"){
            $matchCountry = true;
            $size = 'medium';
            $productImage = new ProductImage;
            if($company->is_all_countries=="no") {
                if($user && $user->customer){
                    $countryCode = $user->customer->country_code;
                    $country = CompanyCountry::whereCompanyId($id)->whereCountry($countryCode)->first();
                    if($country == null)$matchCountry = false;
                }else{
                    $ipInfo = \App\User::getCountryFromIp();
                    // $countryCode = $ipInfo->countryCode;
                    $country = CompanyCountry::whereCompanyId($id)->first();
                }
            }
            $company['media_url'] = $productImage->getImageSize($company->logo,$size);
            if( $matchCountry ){
                $product = new Product;
                $product->assignSearch($request);
                $product->company_id = $id;
                if($user && $user->customer){
                    $product->expiration_date = $user->customer->currentDate();
                }else{
                    $product->expiration_date = $this->getTime($ipInfo->timezone);
                }
                $product->status = "Active";
                $results = $product->search();
                if($results->total()>0){
                    foreach($results as $index=>$item){
                        $logo= $results[$index]['thumbnail'];
                        $results[$index]['media_url'] = $productImage->getImageSize($logo,"medium");
                    }            
                    return response()->json(['company'=>$company,'products'=>$results]);
                }else{
                    if($user && $user->customer){
                        return response()->json(['company'=>$company,'products'=>[]]);
                    }else{
                        return response()->json(['company'=>$company,'products'=>[]]);
                    }
                }
            }
        }        
        return response()->json(['status'=>'failed'], 404);    
    }
    private function getTime($timezone){
        $userTimezone = new \DateTimeZone($timezone);
        $objDateTime = new \DateTime('NOW');
        $objDateTime->setTimezone($userTimezone);
        if($objDateTime->format('H')>18){
            $objDateTime->modify('+1 day');
        }
        return $objDateTime->format('Y-m-d');
    }
    /**
     * search shops(company) on front.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function home(Request $request){
        $company = new Company;
        $company->assignFrontSearch($request);
        $user = $request->user('api');
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json($company->frontSearch());        
    }
}
