<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Grimzy\LaravelMysqlSpatial\Types\Point;


class UserController extends Controller
{

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }


    public function image(User $user)
    {
        $path = Storage::url($user->image);
        $public_path = public_path($path);
        return response()->download($public_path, $user->name);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'image' => 'required|image|dimensions:min_width=200,min_height=200',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $path = $request->image->store('public/images');

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'image' => $path,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }



    public function store(Request $request)
    {

    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }

    public function show(User $user)
    {
        return response()->json(new UserResource($user), 200);
    }

    public function matches() {
        $matches = DB::table('matches')
            ->where('user1_id', '=', Auth::id())
            ->get();
        return $matches;
    }

    private function matchRedundancy($user1_id,$user2_id){
        $forward = DB::table('matches')
            ->where('user1_id', $user1_id)
            ->where('user2_id', $user2_id)->exists();

        return $forward;
    }

    public function pick(Request $request){
        $uid = Auth::id();
        //check partner id
        if(!isset($request["user_id"])){
            return response()->json(["response:"=>"User id mandatory , please try again"],400);
        }
        $user_id = $request["user_id"];

        //user can't pick himself
        if($uid == $user_id ){
            return response()->json(["result"=>"User can't create a match with himself"],400);
        }
        //parter exists in database
        $ok = User::where('id',$user_id )->exists();
        if (!$ok) {
            return response()->json(["result"=>"Selected user does not exist"],400);
        }

        $redundancy = $this->matchRedundancy($user_id,$uid);
        if($redundancy){
            return response()->json(["result"=>"You cant pick the same user 2 times"],200);
        }
        //create match
        $created_at = Carbon::now()->format('Y-m-d H:i:s');
        $match = [
            'user2_id' => $user_id,
            'user1_id' => $uid,
            'created_at' => $created_at,
            'updated_at' => $created_at
        ];

        DB::table('matches')->insert($match);

        $resp = [
            "response:"=>"Match created with",
            "match:" => $match
        ];

        return response()->json($resp,200);
    }

    public function getCompatibles(){

        $user = DB::table('users')->where('id', Auth::id())->first();

        $userData = DB::table('users')->where('preferred_gender',$user->preferred_gender);
        $prefered = $userData->preferedGender;

    }
}
