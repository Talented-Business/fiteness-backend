<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Google_Client;
use Vinkla\Facebook\Facades\Facebook;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Mail;
use App\Mail\VerifyMail;
use App\Mail\PasswordMail;
use App\Session;
/**
 * @group Auth
 *
 * APIs for managing  auth
 */

class AuthController extends Controller
{
    /**
     * register a user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function register(Request $request)
    {
        $record = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            'gender'=>'required|string',
            'gender'=>'required|string',
            'level'=>'required|numeric',
            'place'=>'required|string',
            'goal'=>'required|string',
            'birthday'=>'required|string',
            'weight'=>'required',
            'weightUnit'=>'required|string',
            'height'=>'required',
            'heightUnit'=>'required|string',
            'application_source'=>'required|string',
            'couponCode'=>'nullable',
            'invitationEmail'=>'nullable',
        ]);
        $user = User::createCustomer($record);
        /*try{
            Mail::to($user->email)->send(new VerifyMail($user));
        }catch(\Exception $e){
            return response()->json([
                'errors' => ['email'=>[['error'=>'failed']]]
            ], 423);
        }*/
        if($user){
            list($user,$tokenResult) = User::generateAcessToken($user);
            Session::sessionStartWithUser($user,$tokenResult->token->id);
            return response()->json([
                'authentication'=>[
                    'accessToken' => $tokenResult->accessToken,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                ],
                'user' => $user
            ], 200);
        }else{
            return response()->json([
                'errors' => [['error'=>'api']]
            ], 423);
        }
    }

    /**
     * verify a user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function verify(Request $request, $token) {
        $user = User::where('verify_code', '=', $token)->update(['email_verified' => true]);
        return redirect('/');
    }

    /**
     * reset password.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function reset(Request $request)
    {   
        $email = $request->input('email');
        $request->validate([      
            'email' => 'required|string|email',
        ]);
        $password_code = Str::random(10);
        $user = User::where('email', '=', $email)->first();
        if ($user) {
            $user->update(['password' => Hash::make($password_code)]);
            $data = ['token' => $password_code,'name'=>$user->name];
            Mail::to($email)->send(new PasswordMail($data));
            return response()->json(true);
        } else {
            return response()->json(false,422);
        }
    }
    /**
     * login.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)){
            return response()->json([
                'errors' => ['password'=>[['error'=>'invalid']]]
            ],401);
        }
        $user = Auth::user(); 
        if($user->active==false){
            return response()->json([
                'errors' => ['account'=>[['error'=>'inactive']]]
            ],422);
        }
        list($user,$tokenResult) = User::generateAcessToken($user);
        Session::sessionStartWithUser($user,$tokenResult->token->id);
        if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json([
            'authentication'=>[
                'accessToken' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
            ],
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * logout.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function logout(Request $request)
    {
        $user = $request->user('api');
        Session::sessionEnd($request);
        if($user)$request->user('api')->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * find or create a user.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function findOrCreateUser($user, $provider) {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name' => $user->name,
            'email' => $user->email,
            'provider' => strtoupper($provider),
            'provider_id' => $user->id
        ]);
    }

    /**
     * change password.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function change(Request $request, $token) {
        $password = $request->input('password');
        $user = User::where(['password_code' => $token])->update(['password' => bcrypt($password)]);
        if ($user) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    /**
     * login with google.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function loginGoogle(Request $request){
        $provider = 'google';
        $client = new Google_Client(['client_id' => config('services.google.client_id')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($request->input('access_token'));
        if($payload){
            $user = User::where('provider_id','=',$payload['sub'])->first();
            if($user == null)$user = User::where('google_provider_id','=',$payload['sub'])->first();
            if($user==null){
                return response()->json([
                    'errors' => ['account'=>[['error'=>'not registered']]]
                ],423);        
            }
            if($user->active==false){
                return response()->json([
                    'errors' => ['account'=>[['error'=>'inactive']]]
                ],422);
            }
            list($user,$tokenResult) = User::generateAcessToken($user);
            Session::sessionStartWithUser($user,$tokenResult->token->id);
            if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
            return response()->json([
                'authentication'=>[
                    'accessToken' => $tokenResult->accessToken,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                ],
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        return response()->json([
            'errors' => ['password'=>[['error'=>'invalid']]]
        ],401);
    }
    /**
     * register a user with google.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function registerGoogle(Request $request){
        $provider = 'google';
        $client = new Google_Client(['client_id' => config('services.google.client_id')]);  // Specify the CLIENT_ID of the app that accesses the backend
        $payload = $client->verifyIdToken($request->input('access_token'));
        if ($payload) {
            $user = User::where('provider_id','=',$payload['sub'])->first();
            if($user){
                if($user->active==false){
                    return response()->json([
                        'errors' => ['account'=>[['error'=>'inactive']]]
                    ],422);
                }
                list($user,$tokenResult) = User::generateAcessToken($user);
                Session::sessionStartWithUser($user,$tokenResult->token->id);
                return response()->json([
                    'authentication'=>[
                        'accessToken' => $tokenResult->accessToken,
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString(),
                    ],
                    'token_type' => 'Bearer',
                    'user' => $user
                ]);    
            }else{
                $request['first_name'] = $payload['given_name'];
                $request['last_name'] = $payload['family_name'];
                $request['email'] = $payload['email'];
                $request['password'] = $payload['at_hash'];
                $record = $request->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'password' => 'required|string',
                    'gender'=>'required|string',
                    'gender'=>'required|string',
                    'level'=>'required|numeric',
                    'place'=>'required|string',
                    'goal'=>'required|string',
                    'birthday'=>'required|string',
                    'weight'=>'required',
                    'weightUnit'=>'required|string',
                    'height'=>'required',
                    'heightUnit'=>'required|string',
                    'application_source'=>'required|string',
                    'couponCode'=>'nullable',
                ]);    
                $providerId = $payload['sub'];
                $user = User::createCustomer($record,$provider,$providerId);
                if($user){
                    //Mail::to($user->email)->send(new VerifyMail($user));
                    list($user,$tokenResult) = User::generateAcessToken($user);
                    Session::sessionStartWithUser($user,$tokenResult->token->id);
                    return response()->json([
                        'authentication'=>[
                            'accessToken' => $tokenResult->accessToken,
                            'expires_at' => Carbon::parse(
                                $tokenResult->token->expires_at
                            )->toDateTimeString(),
                        ],
                        'user' => $user
                    ], 201);
                }else{
                    return response()->json([
                        'errors' => [['error'=>'api']]
                    ], 423);
                }    
            }
        } else {
            return response()->json([
                'errors' => ['email'=>[['error'=>'failed']]]
            ], 423);
        }
        return response()->json(true);
    }
    /**
     * login a user with facebook.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function loginFacebook(Request $request){
        $provider = 'facebook';
        $response = Facebook::get('/me', $request->input('access_token'));
        if($response){
            $group = $response->getGraphGroup();
            $facebookId = $group->getId();
            $user = User::where('provider_id','=',$facebookId)->first();
            if($user == null)$user = User::where('facebook_provider_id','=',$facebookId)->first();
            if($user==null){
                return response()->json([
                    'errors' => ['account'=>[['error'=>'not registered']]]
                ],423);        
            }
            if($user->active==false){
                return response()->json([
                    'errors' => ['account'=>[['error'=>'inactive']]]
                ],422);
            }
            list($user,$tokenResult) = User::generateAcessToken($user);
            Session::sessionStartWithUser($user,$tokenResult->token->id);
            if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
            return response()->json([
                'authentication'=>[
                    'accessToken' => $tokenResult->accessToken,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                ],
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        return response()->json([
            'errors' => ['password'=>[['error'=>'invalid']]]
        ],401);
    }
    /**
     * register a user with facebook.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function registerFacebook(Request $request){
        $response = Facebook::get('/me?&fields=first_name,last_name,email', $request->input('access_token'));
        $provider = 'facebook';
        if ($response) {
            $group = $response->getGraphGroup();
            $facebookId = $group->getId();
            $email = $group->getEmail();
            $firstName = $group->getProperty('first_name');
            $lastName = $group->getProperty('last_name');
            $user = User::where('provider_id','=',$facebookId)->first();
            if($user){
                if($user->active==false){
                    return response()->json([
                        'errors' => ['account'=>[['error'=>'inactive']]]
                    ],422);
                }
                list($user,$tokenResult) = User::generateAcessToken($user);
                Session::sessionStartWithUser($user,$tokenResult->token->id);
                return response()->json([
                    'authentication'=>[
                        'accessToken' => $tokenResult->accessToken,
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString(),
                    ],
                    'token_type' => 'Bearer',
                    'user' => $user
                ]);    
            }else{
                $request['first_name'] = $firstName;
                $request['last_name'] = $lastName;
                $request['email'] = $email;
                $request['password'] = Str::random(8);;
                $record = $request->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'password' => 'required|string',
                    'gender'=>'required|string',
                    'gender'=>'required|string',
                    'level'=>'required|numeric',
                    'place'=>'required|string',
                    'goal'=>'required|string',
                    'birthday'=>'required|string',
                    'weight'=>'required',
                    'weightUnit'=>'required|string',
                    'height'=>'required',
                    'heightUnit'=>'required|string',
                    'application_source'=>'required|string',
                    'couponCode'=>'nullable',
                ]);    
                $providerId = $facebookId;
                $user = User::createCustomer($record,$provider,$providerId);
                if($user){
                    //Mail::to($user->email)->send(new VerifyMail($user));
                    list($user,$tokenResult) = User::generateAcessToken($user);
                    Session::sessionStartWithUser($user,$tokenResult->token->id);
                    return response()->json([
                        'authentication'=>[
                            'accessToken' => $tokenResult->accessToken,
                            'expires_at' => Carbon::parse(
                                $tokenResult->token->expires_at
                            )->toDateTimeString(),
                        ],
                        'user' => $user
                    ], 201);
                }else{
                    return response()->json([
                        'errors' => [['error'=>'api']]
                    ], 423);
                }    
            }
        } else {
            return response()->json([
                'errors' => ['email'=>[['error'=>'failed']]]
            ], 423);
        }
        return response()->json(true);
    }
    /**
     * login a user with apple.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function loginApple(Request $request){
        $provider = 'apple';
        $payload = Socialite::driver($provider)->userFromToken($request->input('access_token'));
        if($payload){
            $user = User::where('provider_id','=',$payload->id)->first();
            if($user == null)$user = User::where('email','=',$payload->email)->first();
            if($user==null){
                return response()->json([
                    'errors' => ['account'=>[['error'=>'not registered']]]
                ],423);        
            }
            if($user->active==false){
                return response()->json([
                    'errors' => ['account'=>[['error'=>'inactive']]]
                ],422);
            }
            list($user,$tokenResult) = User::generateAcessToken($user);
            Session::sessionStartWithUser($user,$tokenResult->token->id);
            if($user->customer)\App\Jobs\Activity::dispatch($user->customer);
            return response()->json([
                'authentication'=>[
                    'accessToken' => $tokenResult->accessToken,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString(),
                ],
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }
        return response()->json([
            'errors' => ['password'=>[['error'=>'invalid']]]
        ],401);
    }
    /**
     * register a user with apple.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function registerApple(Request $request){
        $provider = 'apple';
        $payload = Socialite::driver($provider)->userFromToken($request->input('access_token'));
        if ($payload) {
            $user = User::where('email','=',$payload->email)->first();
            if($user){
                if($user->active==false){
                    return response()->json([
                        'errors' => ['account'=>[['error'=>'inactive']]]
                    ],422);
                }
                list($user,$tokenResult) = User::generateAcessToken($user);
                Session::sessionStartWithUser($user,$tokenResult->token->id);
                return response()->json([
                    'authentication'=>[
                        'accessToken' => $tokenResult->accessToken,
                        'expires_at' => Carbon::parse(
                            $tokenResult->token->expires_at
                        )->toDateTimeString(),
                    ],
                    'token_type' => 'Bearer',
                    'user' => $user
                ]);    
            }else{
                $request['first_name'] = $payload->name?$payload->name:$payload->email;
                $request['last_name'] = $payload->name?$payload->name:$payload->email;
                $request['email'] = $payload->email;
                $request['password'] = $payload->user['c_hash'];
                $record = $request->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'password' => 'required|string',
                    'gender'=>'required|string',
                    'gender'=>'required|string',
                    'level'=>'required|numeric',
                    'place'=>'required|string',
                    'goal'=>'required|string',
                    'birthday'=>'required|string',
                    'weight'=>'required',
                    'weightUnit'=>'required|string',
                    'height'=>'required',
                    'heightUnit'=>'required|string',
                    'application_source'=>'required|string',
                    'couponCode'=>'nullable',
                ]);    
                $providerId = $payload->id;
                $user = User::createCustomer($record,$provider,$providerId);
                if($user){
                    //Mail::to($user->email)->send(new VerifyMail($user));
                    list($user,$tokenResult) = User::generateAcessToken($user);
                    Session::sessionStartWithUser($user,$tokenResult->token->id);
                    return response()->json([
                        'authentication'=>[
                            'accessToken' => $tokenResult->accessToken,
                            'expires_at' => Carbon::parse(
                                $tokenResult->token->expires_at
                            )->toDateTimeString(),
                        ],
                        'user' => $user
                    ], 201);
                }else{
                    return response()->json([
                        'errors' => [['error'=>'api']]
                    ], 423);
                }    
            }
        } else {
            return response()->json([
                'errors' => ['email'=>[['error'=>'failed']]]
            ], 423);
        }
        return response()->json(true);
    }
}