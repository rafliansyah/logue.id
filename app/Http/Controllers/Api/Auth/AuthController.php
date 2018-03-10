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

class AuthController extends Controller
{
    //

    public function verifyUser($verification_code)
    {
      //return $verification_code;
        $check = DB::table('user_verifications')->where('token',$verification_code)->first();
        if($check){
            $user = User::find($check->user_id);
            if($user->is_verified == 1){
                return response()->json([
                    'success'=> true,
                    'message'=> 'Account already verified..'
                ]);
            }
            if($user->update(['is_verified' => 1])){
              DB::table('user_verifications')->where('token',$verification_code)->delete();
              return response()->json([
                  'success'=> true,
                  'message'=> 'You have successfully verified your email address.'
              ]);
            }
        }
        return response()->json(['success'=> false, 'error'=> "Verification code is invalid."]);
    }

    /**
     * Log in
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */

    public function login(Request $request){
       $rules = [
           'email' => 'required|email',
           'password' => 'required',
       ];

       $input = $request->only('email', 'password');

       $validator = Validator::make($input, $rules);

       if($validator->fails()) {
           $error = $validator->errors()->all();
           return response()->json(['success'=> false, 'error'=> $error]);
       }

       $credentials = [
           'email' => $request->email,
           'password' => $request->password,
           'is_verified' => 1
       ];

       try {
           // attempt to verify the credentials and create a token for the user
           if (! $token = JWTAuth::attempt($credentials)) {
               return response()->json(['success' => false, 'error' => 'Invalid Credentials. Please make sure you entered the right information and you have verified your email address.']);
           }
       } catch (JWTException $e) {
           // something went wrong whilst attempting to encode the token
           return response()->json(['success' => false, 'error' => 'could_not_create_token'], 500);
       }

       // all good so return the token
       return response()->json(['success' => true, 'data'=> [ 'token' => $token ]]);
   }

   /**
    * Log out
    * Invalidate the token, so user cannot use it anymore
    * They have to relogin to get a new token
    *
    * @param Request $request
    * 500 = internal server error
    */
   public function logout(Request $request) {
       $this->validate($request, ['token' => 'required']);

       try {
           JWTAuth::invalidate($request->input('token'));
           return response()->json(['success' => true]);
       } catch (JWTException $e) {
           // something went wrong whilst attempting to encode the token
           return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
       }
   }

   /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 401 = unauthorized
     */
    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email'=> $error_message]], 401);
        }

        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });

        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }

        return response()->json([
            'success' => true, 'data'=> ['msg'=> 'A reset email has been sent! Please check your email.']
        ]);
    }
      /**
      * API Recover Password
      *
      * @param Request $request
      * @return \Illuminate\Http\JsonResponse
      * 401 = unauthorized
      */
      public function userProfile(Request $request){
        
      }

}
