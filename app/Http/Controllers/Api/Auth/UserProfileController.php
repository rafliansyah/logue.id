<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;

use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Http\Controllers\Api\Utility\Utilities;

class UserProfileController extends Controller{
    //
    public function register(Request $request){
      $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed'
      ];

      $input = $request->only(
            'name',
            'email',
            'password',
            'password_confirmation'
        );

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            $error = $validator->errors()->all();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);

        $verification_code = str_random(30); //Generate verification code

        $utilities = new Utilities();
        $verification_six_digits = $utilities->randomNumber(6);
        DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code, 'code'=>$verification_six_digits]);

        $subject = "Please verify your email address.";
        Mail::send('verify', ['name' => $name, 'verification_code' => $verification_code, 'verification_six_digits' => $verification_six_digits],
            function($mail) use ($email, $name, $subject){
                $mail->from('portal.logue@gmail.com');
                $mail->to($email, $name);
                $mail->subject($subject);
            });

            return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.']);
    }

    public function verifyUserCode(Request $request)
    {
      $rules = [
        'code' => 'required|max:6|min:6'
      ];

      $input = $request->only(
            'code'
        );

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            $error = $validator->errors()->all();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

        $check = DB::table('user_verifications')->where('code',$request->code)->first();
        if($check){
            $user = User::find($check->user_id);
            if($user->is_verified == 1){
                return response()->json([
                    'success'=> true,
                    'message'=> 'Account already verified..'
                ]);
            }
            if($user->update(['is_verified' => 1])){
              DB::table('user_verifications')->where('code',$request->code)->delete();
              return response()->json([
                  'success'=> true,
                  'message'=> 'You have successfully verified your email address.'
              ]);
            }
        }
        return response()->json(['success'=> false, 'error'=> "Verification code is invalid."]);
    }

    public function userProfile(Request $request){

      $user = JWTAuth::parseToken()->authenticate();
      return $user->profile;

    }

    public function userProfileUpdate(Request $request){

      $rules = [
        'sex' => 'required|string|max:255',
        'placeOfBirth' => 'required|string',
        'dateOfBirth' => 'required|date',
        'phone' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'country' => 'required|string'
      ];

      $input = $request->only(
            'sex',
            'placeOfBirth',
            'dateOfBirth',
            'phone',
            'address',
            'city',
            'state',
            'country'
        );

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            $error = $validator->errors()->all();
            return response()->json(['success'=> false, 'error'=> $error]);
        }

    }

}
