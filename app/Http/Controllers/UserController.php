<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class UserController extends Controller {

    public function checkCredentials(Request $request){
        $exists = DB::table('users')->where('email',$request['email'])->exists();
        if($exists){
            return response()->json(['message' => 'already_registered'], 400);
        }
        return response()->json(['status' => 'success'], 200);
    }

    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');
        try {
            $exists = DB::table('users')->where('email',$request['email'])->exists();
            if(!$exists){
                return response()->json(['message' => 'not_registered'], 400);
            }
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        $user = Auth::user()->completeRecord();

        return response()->json(compact('user'));
    }


    public function image(User $user) {

        return response()->download(Auth::user()->imagePath(), $user->name);
    }

    public function uploadPhoto(Request $request) {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|dimensions:min_width=200,min_height=200',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $path = $request->image->store('public/images');

        DB::table('users')->where('id', Auth::id())->update(['image' => $path]);

        return response()->json("photo uploaded!!", 201);
    }

    public function register(Request $request) {
        $messages = [
            "email.unique" => "Ya existe un usuario registrado con ese correo.",
            "lat.required" => "Debes habilitar el acceso a tu ubicación",
            "lat.numeric" => "Debes habilitar el acceso a tu ubicación"
        ];
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:6|confirmed',
            'preferred_gender' => 'required|string|min:4|max:6',
            'gender'           => 'required|string|min:4|max:6',
            'birthdate'        => 'required|date',
            'image'            => 'required|image|dimensions:min_width=200,min_height=200',
            'lat'              => 'required|numeric',
        ],$messages);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(compact('messages'), 409);
        }

        $path = $request->image->store('public/images');

        $user = User::create([
            'name'             => $request->get('name'),
            'email'            => $request->get('email'),
            'password'         => Hash::make($request->get('password')),
            'gender'           => $request['gender'],
            'description'      => $request['description'],
            'preferred_gender' => $request['preferred_gender'],
            'preferred_pet'    => $request['preferred_pet'],
            'min_age'          => $request['min_age'],
            'max_age'          => $request['max_age'],
            'lat'              => $request['lat'],
            'lng'              => $request['lng'],
            'image'            => $path,
            'birthdate'        => $request['birthdate']
        ]);

        $musicGenresNames = explode(",", $request["music_genres"]);
        $movieGenresNames = explode(",", $request["movie_genres"]);

        $user->attachMusicGenres($musicGenresNames);
        $user->attachMovieGenres($movieGenresNames);

        //authentication attempt
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        $user = $user->completeRecord();

        return response()->json(compact('user'), 201);
    }


    public function store(Request $request) {

    }

    public function getAuthenticatedUser(Request $request) {
        try {

            $exists = DB::table('users')->where('email',$request['email'])->exists();
            if(!$exists){
                return response()->json(['message' => 'not_registered'], 400);
            }
            if(!$exists){
                return response()->json(['message' => 'not_registered'], 400);
            }

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

        return response()->json(Auth::user()->completeRecord());
    }

    public function show(User $user) {
        return response()->json(new UserResource($user), 200);
    }

    public function matches() {
        $matches = DB::table('matches')
            ->where('user1_id', '=', Auth::id())
            ->get();
        return $matches;
    }

    public function pick(Request $request) {
        $uid = Auth::id();

        if (!isset($uid)) {
            return response()->json(["response:" => "Couldn't get authenticated user id"], 400);
        }
        //check partner id
        if (!isset($request["user_id"])) {
            return response()->json(["response:" => "User id mandatory"], 400);
        }

        $user_id = $request["user_id"];

        //user can't pick himself
        if ($uid == $user_id) {
            return response()->json(["result" => "User can't create a match with himself"], 400);
        }

        //parter exists in database
        $ok = User::where('id', $user_id)->exists();
        if (!$ok) {
            return response()->json(["result" => "Selected user does not exist"], 400);
        }

        //match redundancy check
        $forwardMatchRef = DB::table('matches')
            ->where('user1_id', $uid)
            ->where('user2_id', $user_id);

        $forward = $forwardMatchRef->exists();

        $backwardMatchRef = DB::table('matches')
            ->where('user1_id', $user_id)
            ->where('user2_id', $uid);

        $backward = $backwardMatchRef->exists();

        if ($forward) {
            return response()->json(["result" => "You cant pick the same user 2 times"], 200);
        }

        //accept match
        if ($backward) {
            $backwardMatch = $backwardMatchRef->get()->first();

            if ($backwardMatch->accepted) {
                return response()->json("You cant pick the same user 2 times", 200);
            }

            $backwardMatchRef->update(['accepted' => TRUE]);

            return response()->json("match accepted", 201);
        }
        //create match
        $created_at = Carbon::now()->format('Y-m-d H:i:s');
        $match      = [
            'user2_id'   => $user_id,
            'user1_id'   => $uid,
            'accepted'   => FALSE,
            'created_at' => $created_at,
            'updated_at' => $created_at
        ];

        DB::table('matches')->insert($match);

        return response()->json("match created", 201);
    }

    private function isCompatible($user, $candidate) {
        $compatibleFields = 0;

        //prevents the app to suggest users from the same gender if the user picked its opposite gender as preferred
        if ($user->gender != $user->preferred_gender && $candidate->gender == $user->gender) {
            return FALSE;
        }

        //preferred gender
        if ($user->preferred_gender == $candidate->gender) {
            $compatibleFields++;
        }

        if ($user->preferred_pet == $candidate->preferred_pet) {
            $compatibleFields++;
        }

        return $compatibleFields >= 1;
    }

    public function getCompatibles() {

        $user        = Auth::user();
        $compatibles = [];

        DB::table('users')->whereBetween('age', [$user->min_age, $user->max_age])
            ->where('id', '!=', Auth::id())
            ->orderBy('age')
            ->chunk(10, function ($candidates) use (&$user, &$compatibles) {
                foreach ($candidates as $candidate) {
                    if ($this->isCompatible($user, $candidate)) {
                        $compatibles[] = $candidate;
                    }
                }
            });
        return response()->json($compatibles, 201);
    }

    public function activeSession() {

        if (Auth::check()) {
            $user = Auth::user()->completeRecord();
            return response()->json(compact('user'), 200);
        }
        else {
            return response()->json(["message" => "active_session_not_found"], 404);
        }
    }

    public function logout(){
        auth()->logout(true);
        response()->json(["message" => "logged_out"], 200);
    }
}
