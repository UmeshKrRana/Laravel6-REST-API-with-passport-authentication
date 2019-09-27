<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $successStatus  =   200;

    //----------------- [ create user ] -------------------
    public function createUser(Request $request) {

        $validator  =   Validator::make($request->all(),
            [
                'name'              =>      'required|min:3',
                'email'             =>      'required|email',
                'password'          =>      'required|alpha_num|min:5',
                'confirm_password'  =>      'required|same:password'
            ]
        );

        if($validator->fails()) {
            return response()->json(['Validation errors' => $validator->errors()]);
        }

        $input              =       array(
            'name'          =>          $request->name,
            'email'         =>          $request->email,
            'password'      =>          bcrypt($request->password),
            'address'       =>          $request->address, 
            'city'          =>          $request->city
        );

        // check if email already registered
        $user                   =       User::where('email', $request->email)->first();
        if(!is_null($user)) {
            $data['message']     =      "Sorry! this email is already registered";
            return response()->json(['success' => false, 'status' => 'failed', 'data' => $data]);
        }

        // create and return data
        $user                   =       User::create($input);         
        $success['message']     =       "You have registered successfully";

        return response()->json( [ 'success' => true, 'user' => $user ] );
    }


    // -------------- [ User Login ] ------------------

    public function userLogin(Request $request) {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            
            // getting auth user after auth login
            $user = Auth::user();

            $token                  =       $user->createToken('token')->accessToken;
            $success['success']     =       true;
            $success['message']     =       "Success! you are logged in successfully";
            $success['token']       =       $token;

            return response()->json(['success' => $success ], $this->successStatus);
        }

        else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    // ------------------- [ user Detail ] --------------------

    public function userDetail() {
        $user           =       Auth::user();

        $success['success']     =   true;
        $success['status']      =   "success";
        $success['data']        =   $user;

        return response()->json($success);        
    }
}