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


class UserController extends Controller {

    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'))
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'

            );
    }


    public function image(User $user) {
        $path        = Storage::url($user->image);
        $public_path = public_path($path);
        return response()->download($public_path, $user->name);
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
        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:6|confirmed',
            'preferred_gender' => 'required|string|min:4|max:6',
            'gender'           => 'required|string|min:4|max:6',
            'age'              => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $user = User::create([
            'name'             => $request->get('name'),
            'email'            => $request->get('email'),
            'password'         => Hash::make($request->get('password')),
            'gender'           => $request['gender'],
            'age'              => $request['age'],
            'description'      => $request['description'],
            'preferred_gender' => $request['preferred_gender'],
            'preferred_pet'    => $request['preferred_pet'],
            'min_age'          => $request['min_age'],
            'max_age'          => $request['max_age'],
            'lat'              => $request['lat'],
            'lng'              => $request['lng'],
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201)
            ->withCookie(
                'token',
                $token,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'

            );
    }


    public function store(Request $request) {

    }

    public function getAuthenticatedUser() {
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

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                "status" => "success",
                "message" => "User successfully logged out."
            ], 200)
            ->withCookie(
                'token',
                null,
                config('jwt.ttl'),
                '/',
                null,
                config('app.env') !== 'local',
                true,
                false,
                config('app.env') !== 'local' ? 'None' : 'Lax'
            );
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(["message" => "No se pudo cerrar la sesión."], 500);
        }
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
}
