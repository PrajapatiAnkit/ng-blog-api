<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * This function is to register the new user
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = [
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => bcrypt($request->password)
        ];
        try {
            User::create($user);
            return ResponseHelper::successResponse('User registered successfully',['user' => $user]);
        }catch (\Exception $exception){
            return ResponseHelper::errorResponse($exception->getMessage(),201);
        }
    }

    /**
     * This function validates user login
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = ['email' => $request->email, 'password' => $request->password];
        if (! $token = JWTAuth::attempt($credentials)) {
            return ResponseHelper::errorResponse('login failed', 200);
        }
        return ResponseHelper::successResponse('login successful !',['user' => Auth::user(),'token' => $token]);
    }

    /**
     * This function logouts the user
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return ResponseHelper::successResponse('logged out successfully !');
    }

    /**
     * This function returns the loggedIn user profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = Auth::user();
        return ResponseHelper::successResponse('Current user profile',$user);
    }

    /**
     * This function updates the user profile
     * @param ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(ProfileRequest $request)
    {
        $userId = Auth::id();
        $profile = [
          'name' => $request->name,
          'email' => $request->email,
          'contact' => $request->contact,
        ];
        if ($request->password){
            $profile['password'] = bcrypt($request->password);
        }
        try {
            User::where('id', $userId)->update($profile);
            return ResponseHelper::successResponse('Profile updated successfully',$profile);
        }catch (\Exception $exception){
            return ResponseHelper::errorResponse($exception->getMessage(),201);
        }
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
