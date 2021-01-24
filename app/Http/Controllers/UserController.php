<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\User as UserResuce;
use App\Http\Resources\UserCollection; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function index(){
        return new UserCollection(User::paginate(10));
    }
    public function show( User $user){
        return response()->json(new UserResuce($user),200);
    }
    
    /*public function update(Request $request, User $user){
        $user->update($request->all());
        return response()->json($user, 200);
    }*/
    /*public function delete(User $user){
        $user->delete();
        return response()->json(null, 204);
    }*/

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

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'age' => 'required|int|min:18|max:40',
            'gender' => 'required|string|max:1',
            'min_age' => 'required|int|min:18',
            'max_age' => 'required|int|min:18|max:40',
            'preferred_gender' => 'required|string|max:1',
            'preferred_pet' => 'string',
            'address' => 'string',
            'location' => 'required|point',
            'description' => 'required|string|max:225',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'age' => $request->get('age'),
            'gender' => $request->get('gender'),
            'description' => $request->get('description'),
            'location' => $request->get('location'),
            'address' => $request->get('address'),
            'min_age' => $request->get('min_age'),
            'max_age' => $request->get('max_age'),
            'preferred_gender' => $request->get('preferred_gender'),
            'preferred_pet' => $request->get('preferred_pet'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public
    function getAuthenticatedUser()
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
}
