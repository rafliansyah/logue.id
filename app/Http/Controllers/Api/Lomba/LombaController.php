<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Model\User;

use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Http\Controllers\Api\Utility\Utilities;

class LombaController extends Controller{
    //
    public function createLomba(Request $request){
      $validator = Validator::make($request->all(), [
        'poster' => 'required',
        'name_lomba' => 'required',
        'type_lomba' => 'required',
        'date' => 'required',
        'time' => 'required',
        'place' => 'required',
        'criteria_lomba' => 'required',
        'cost_regist' => 'required',
        'lomba_description' => 'required',
        'upload_legal' => 'required',
      ]);

      if($validator->fails()) {
          $error = $validator->errors()->all();
          return response()->json(['success'=> false, 'error'=> $error]);
      }
      $attributes = [
        'poster' => $request->input('poster'),
        'name_lomba' => $request->input('name_lomba'),
        'type_lomba' => $request->input('type_lomba'),
        'date' => $request->input('date'),
        'time' => $request->input('time'),
        'place' => $request->input('place'),
        'criteria_lomba' => $request->input('criteria_lomba'),
        'cost_regist' => $request->input('cost_regist'),
        'lomba_description' => $request->input('lomba_description'),
        'upload_legal' => $request->input('upload_legal'),
        'user_id' => $request->userProfile()->id,
      ];


    }
}
