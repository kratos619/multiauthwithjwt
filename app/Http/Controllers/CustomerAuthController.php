<?php

namespace App\Http\Controllers;
use App\Customer;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    protected $successStatus = 200;
    //  public function __construct()
    // {
    //     $this->middleware('auth:apicustomer', ['except' => ['login']]);
    // }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = $this->guard()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }





    public function customer_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Customer::create($input);
        if($user){
            $credentials = request(['email', 'password']);
            $token = $this->guard()->attempt($credentials);
            return $this->respondWithToken($token);
        }
    }


     public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

     public function me()
    {
        if(!auth()){
            return response()->json("Not Authorised User", 200);
        }else{

            return response()->json(auth()->user());
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
   

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

      protected function guard()
    {
        return Auth::guard('apicustomer');
    }

}
