<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function UserRegistration(Request $request){

        try {
            $email=$request->input('email');
            $name=$request->input('name');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::create([
                'name'=>$name,
                'email'=>$email,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully'
            ],200);
        }

        catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()],200);
        }

    }


    function UserLogin(Request $request){

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Password matches
            $userId = $user->id;

            if($userId !== null)
            {

                $token=JWTToken::CreateToken($request->input('email'),$user->id);
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Login Successful',
                    'token'=>$token
                ],200)->cookie('token',$token,time()+60*60*24*30);
            }
            else
            {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'unauthorized'
                ],401);
    
            }
        } else {
       
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

    }


    function UserLogout(){
        return redirect('/')->cookie('token','',-1);
    }

    function SendOTPCode(Request $request){

        $email=$request->input('email');
        $otp=rand(1000,9999);
        $count=User::where('email','=',$email)->count();
        if($count==1){
            Mail::to($email)->send(new OTPMail($otp));
            User::where('email','=',$email)->update(['otp'=>$otp]);
            return response()->json([
                'status' => 'success',
                'message' => "4 Digit {$otp} Code has been send to your email !"
            ],200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ]);
        }

    }


    function VerifyOTP(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
            ->where('otp','=',$otp)->count();

        if($count == 1){
            User::where('email','=',$email)->update(['otp'=>'0']);
            $token=JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful',
                'token'=>$token
            ],200)->cookie('token',$token,60*60*30);

        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);
        }
    }


    function ResetPassword(Request $request){

        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
                'msg'=> $e->getMessage()
            ],200);
        }

    }


    function UserProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ],200);
    }



    function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $name=$request->input('name');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'name'=>$name,
                'email'=>$email,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }
}
