<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
//use App\Rules\UniqueEmail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Customer;
use App\Session;
use Twilio\Rest\Client;

class UserController extends Controller
{

    public function store(Request $request){
        $validator = Validator::make($request->all(), array('email'=>['required','max:255','email','unique:users']));
        if ($validator->fails()) {
            return response()->json(array('status'=>'failed','errors'=>$validator->errors()));
        }
        if( $request->new_password != $request->confirm_password ){
            return response()->json(array('status'=>'failed','errors'=>['password'=>'password_unmatched']));
        }
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->new_password);
        $user->type="admin";
        if($request->input("type") == "super")$user->type="admin";
        $user->save();
        $user->saveMenus($request->input('menus'));
        return response()->json(array('status'=>'ok','user'=>$user));
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function findByToken(Request $request)
    {
        $user = $request->user('api');
        if($user){
            $user->extend();
            if($user->active){
                return response()->json($user);
            }
        }else{
            return null;
        }
    }
    public function generateAccessToken(Request $request){
        $user = $request->user('api');
        $oldToken = $request->user('api')->token();
        list($user,$tokenResult) = User::generateAcessToken($user);
        $newToken = $tokenResult->token;
        Session::updateToken($oldToken,$newToken);
        return response()->json([
            'authentication'=>[
                'accessToken' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ],
            'user' => $user
        ]);
    }
    public function update($id,Request $request){
        $validator = Validator::make($request->all(), array('email'=>['required','max:255','unique:users,email,'.$id]));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }
        $currentUser = $request->user('api');
        $user = User::find($id);
        if($currentUser->id != $user->id || $currentUser->id == $user->id && $user->type!="admin" ){
            return response()->json(['errors'=>['auth'=>'authorization']],422);
        }
        $validator = Validator::make($request->all(), array('email'=>['required','max:255']));
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $password = false;
        if($request->exists('current_password')){
            if (Hash::check($request->current_password, $user->password)==false) {
                return response()->json(['errors'=>['current_password'=>[['error'=>'current password failed']]]],422);
            }
            if( $request->password != $request->confirm_password ){
                return response()->json(['errors'=>['password'=>'password_unmatched']],422);
            }
            $password = true;
        }
        if($request->exists('name')){
            $user->name = $request->input('name');
        }
        if($request->exists('email')){
            $user->email = $request->input('email');
        }
        if($request->exists('active')){
            $user->active = $request->input('active');
        }
        if($password)$user->password = Hash::make($request->password);
        $user->save();
        return response()->json(array('status'=>'ok','user'=>$user));
    }
    public function customerUpdate(Request $request){
        $user = $request->user('api');
        $validator = Validator::make($request->all(), Customer::validateUserSettingRules($user->id,$user->customer->id));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }
        $password = false;
        if($request->exists('password')){
            /*if (Hash::check($request->current_password, $user->password)==false) {
                return response()->json(array('status'=>'failed','errors'=>['password'=>'current_password_falied']));
            }*/
            if( $request->password != $request->confirm_password ){
                return response()->json(['errors' => ['password'=>[['error'=>'password_unmatched']]]],422);
            }
            $password = true;
        }
        if($request->exists('email')){
            $user->email = $request->input('email');
        }
        if($password)$user->password = Hash::make($request->password);
        if($request->hasFile('image')&&$request->file('image')->isValid()){ 
            $photoPath = $request->image->store('media/user');
            $file = storage_path('app/public/'.$photoPath);
            $this->cropImage($file,160,237,$file);
            if(PHP_OS == 'Linux'){
                $output = shell_exec("mogrify -auto-orient $file");
                sleep(1);
            }
            $user->avatar = $photoPath;
        }        
        $user->name = $request->input('first_name').' '.$request->input('last_name');
        $user->save();
        $user->customer->first_name = $request->input('first_name');
        $user->customer->last_name = $request->input('last_name');
        $user->customer->active_email = $request->input('active_email');
        $user->customer->email = $request->input('customer_email');
        $user->customer->active_whatsapp = $request->input('active_whatsapp');
        $user->customer->whatsapp_phone_number = $request->input('whatsapp_phone_number');
        $user->customer->country = $request->input('country');
        $user->customer->country_code = $request->input('country_code');
        if($user->customer->active_whatsapp && $user->customer->whatsapp_phone_number&&getenv("APP_ENV")!="local"){
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            try{
                $phoneNumber =  $twilio->lookups->v1->phoneNumbers($user->customer->whatsapp_phone_number)->fetch();
            } catch(\Exception $e){
                return response()->json(['errors' => ['whatsapp_phone_number'=>[['error'=>'invalid']]]],422);
            }
        }
        $user->customer->save();
        return response()->json(array('status'=>'ok','user'=>$user));
    }
    private function cropImage($sourcePath, $width=200,$height=230, $destination = null) {

        $parts = explode('.', $sourcePath);
        $ext = $parts[count($parts) - 1];
        if ($ext == 'jpg' || $ext == 'jpeg') {
          $format = 'jpg';
        } else {
          $format = 'png';
        }
      
        if ($format == 'jpg') {
          $sourceImage = imagecreatefromjpeg($sourcePath);
        }
        if ($format == 'png') {
          $sourceImage = imagecreatefrompng($sourcePath);
        }
      
        list($srcWidth, $srcHeight) = getimagesize($sourcePath);
      
        // calculating the part of the image to use for thumbnail
        if ($srcWidth > $srcHeight) {
          $y = 0;
          $x = ($srcWidth - $srcHeight) / 2;
          $smallestSide = $srcHeight;
        } else {
          $x = 0;
          $y = ($srcHeight - $srcWidth) / 2;
          $smallestSide = $srcWidth;
        }
      
        $destinationImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($destinationImage, $sourceImage, 0, 0, $x, $y, $width, $height, $smallestSide, $smallestSide);
      
        if ($destination == null) {
          header('Content-Type: image/jpeg');
          if ($format == 'jpg') {
            imagejpeg($destinationImage, null, 100);
          }
          if ($format == 'png') {
            imagejpeg($destinationImage);
          }
          if ($destination = null) {
          }
        } else {
          if ($format == 'jpg') {
            imagejpeg($destinationImage, $destination, 100);
          }
          if ($format == 'png') {
            imagepng($destinationImage, $destination);
          }
        }
    }
    public function emailUpdate(Request $request){
        $user = $request->user('api');
        $validator = Validator::make($request->all(), Customer::validateMailSettingRules($user->id,$user->customer->id));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],422);
        }
        $user->customer->active_email = $request->input('active_email');
        $user->customer->email = $request->input('email');
        $user->customer->active_whatsapp = $request->input('active_whatsapp');
        $user->customer->whatsapp_phone_number = $request->input('whatsapp_phone_number');
        $user->customer->country = $request->input('country');
        $user->customer->country_code = $request->input('country_code');
        $user->customer->email_update = 1;
        if($user->customer->active_whatsapp && $user->customer->whatsapp_phone_number&&getenv("APP_ENV")!="local"){
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            try{
                $phoneNumber =  $twilio->lookups->v1->phoneNumbers($user->customer->whatsapp_phone_number)->fetch();
            } catch(\Exception $e){
                return response()->json(['errors' => ['whatsapp_phone_number'=>[['error'=>'invalid']]]],422);
            }
        }
        $user->customer->save();
        return response()->json(array('status'=>'ok','user'=>$user));
    }
    public function destroy($id){
        $user = User::find($id);
        if($user){
            $destroy=User::destroy($id);
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
        $user = User::find($id);
        return $user;
    }
}