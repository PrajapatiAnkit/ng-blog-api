<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\MediaService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
            return ResponseHelper::successResponse(__('common.signup_successful'),['user' => $user]);
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
        return ResponseHelper::successResponse(__('common.login_successful'),[
            'user' => new UserResource(Auth::user()),
            'token' => $token
        ]);
    }

    /**
     * This function logouts the user
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return ResponseHelper::successResponse(__('common.logged_out_successfully'));
    }

    /**
     * This function returns the loggedIn user profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $userId = Auth::id();
        $user = UserService::getUserData($userId);
        return ResponseHelper::successResponse(__('profile.current_user_is'),new UserResource($user));
    }

    /**
     * This function updates the user profile
     * @param ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(ProfileRequest $request)
    {
        $preProfile = null;
        $user = Auth::user();
        $userId = $user->id;
        $profile = [
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
        ];
        if ($request->profile_pic != ''){
            $user = User::findOrFail($userId,['profile_pic']);
            $preProfile = $user->getRawOriginal('profile_pic');
            $profilePicPath = config('constants.paths.profile');
            $profilePicName = Str::slug($request->name.time()).'.png';
            MediaService::uploadMedia($request->profile_pic, $profilePicName, $profilePicPath, $preProfile);
            $profile['profile_pic'] = $profilePicName;
        }
        if (isset($request->password)){
            $profile['password'] = bcrypt($request->password);
        }
        try {
             User::where('id', $userId)->update($profile);
            $user = UserService::getUserData($userId);
            return ResponseHelper::successResponse(__('profile.profile_updated_successfully'),new UserResource($user));
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
