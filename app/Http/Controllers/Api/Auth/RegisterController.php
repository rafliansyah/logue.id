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

class RegisterController extends Controller{
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

        DB::beginTransaction();

        //Ini berfungsi untuk mencegah pengisian data yang hanya pada tabel satu, karena fungsi ini berjalan di dua tabel yang berbeda.
        try{
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
        }catch(Exception $e){
          DB::rollback();
          return response()->json(['success'=> true, 'message'=> 'Register Failed']);
        }
        DB::commit();


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
}
