<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => false, 'error' => $validator->errors()], 422);
        }
    
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        if (!$user) {
            return response()->json(['status' => false, 'error' => 'User registration failed'], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'fcm_token' => 'string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => false,'error' => $validator->errors()], 422);
        }
    
        $credentials = $validator->validated();
        unset($credentials['fcm_token']);
        
        if (!$token = auth()->attempt($credentials)) {
            $response = ['status' => false,'error' => 'Invalid credentials'];
    
            if (User::where('email', $credentials['email'])->doesntExist()) {
                $response['error_email'] = 'Email does not exist';
            }
    
            if (User::where('email', $credentials['email'])->exists() && !auth()->validate($credentials)) {
                $response['error_password'] = 'Incorrect password';
            }
    
            return response()->json($response, 401);
        }


        if($request->fcm_token){
            $user_id = auth()->id();
            User::where('id', $user_id)->update(['fcm_token' => $request->fcm_token]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successfull',
            'token' => $token,
        ]);
    }

    // public function logout()
    // {
    //     try {
    //         // Check if the user is authenticated
    //         if (Auth::check()) {
    //             Auth::logout();

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Logout successfully.',
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not authenticated.',
    //             ], 401);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'error' => $e->getMessage(),
    //             'message' => 'Failed to logout.',
    //         ], 500);
    //     }
    // }
}
