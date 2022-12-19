<?php

namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    
     
    public function register(Request $request)
    {
       
        $data = $request->only('name', 'email', 'password');
        
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
        ]);
        

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        

        $credentials = $request->only('email', 'password');
        
        return response()->json([
            'message' => 'User created',
            'token' => JWTAuth::attempt($credentials),
            'user' => $user
        ], Response::HTTP_OK);
    }
    
    public function authenticate(Request $request)
    {
        
        $credentials = $request->only('email', 'password');
        
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
      
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                
                return response()->json([
                    'message' => 'Login failed',
                ], 401);
            }
        } catch (JWTException $e) {
            
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
        

        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    
    public function logout(Request $request)
    {
        
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        try {
            
            JWTAuth::invalidate($request->token);
            return response()->json([
                'success' => true,
                'message' => 'User disconnected'
            ]);
        } catch (JWTException $exception) {
            
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function getUser(Request $request)
    {
        
        $this->validate($request, [
            'token' => 'required'
        ]);
        
        $user = JWTAuth::authenticate($request->token);
        
        if(!$user)
            return response()->json([
                'message' => 'Invalid token / token expired',
            ], 401);
        
        return response()->json(['user' => $user]);
    }
}
