<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;

use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use DB, Hash, Mail;
use App\Http\Controllers\Api\Utility\Utilities;

class UserProfileController extends Controller{
    //

    public function userProfileEdit(Request $request, $id){

      $user = user::find($id);
      $user->image;

      return $user;

    }

    public function userProfileUpdate(Request $request, $id){
      $user = user::find($id);
      if($request->hasFile('image')){
        $image = $request->file('image');
        $filename = $image->hashName();
        $image->move('medias/avatar', $filename);
        $path = "medias/avatar/{$filename}";
      }else {
        # code...
        $path = $user->image;
      }
        $user->name = $request->name;
        $user->sex = $request->sex;
        $user->image = $path;
        $user->placeOfBirth = $request->placeOfBirth;
        $user->dateOfBirth = $request->dateOfBirth;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;

      $user->save();
      //return

    }


}
