<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  // private function reformatRequest($request)
  // {
  //   return [
  //     'page' => $this->array_get($request, 'page', null),
  //     'movienumber' => $this->array_get($request, 'movienumber', null),

  //   ];
  // }
  private function createJsonResult($response)
  {
    $result = response()->json($response, 200);
    return $result
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
  }

  private function StringToArray($string)
  {
    $result = explode(",", $string);
    return json_decode(json_encode($result), FALSE);
  }
  public function logout(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "email" => 'required|string',
        "Token" => 'required|string'
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $user =  (array)DB::table('Moderators')
      ->where('email', $request['email'])
      ->select('Token')
      ->first();
    if ($user['Token'] == null) {
      return "You were logout.";
    } else {
      if ($request['Token'] == $user['Token']) {
        $user =  (array)DB::table('Moderators')
          ->where('email', $request['email'])
          ->update([
            'Token' => null
          ]);
          $result = "Successful";
        return  $this->createJsonResult($result);
      }
      else{
        $result = "Token is incorrect!";
        return  $this->createJsonResult($result);
      }
    }
  }
  public function login(Request $request)
  {
    $validator = Validator::make(
      $request->all(),
      [
        "email" => 'required|string',
        "password" => 'required|string'
      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    $user =  (array)DB::table('Moderators')
      ->where('email', $request['email'])
      ->select('id', 'password', 'Token')
      ->first();
    if ($user['password'] == null) {
      $result = "Email does not exist!";
      return  $this->createJsonResult($result);
    }
    if ($user['Token'] != null) {
      $result = "User is login.";
      return  $this->createJsonResult($result);
    } else {
      if (Hash::check($request['password'], $user['password']) == true) {
        $token = Str::random(60);
        $user =  (array)DB::table('Moderators')
          ->where('email', $request['email'])
          ->update([
            'Token' => $token
          ]);
        $result = array(
          'email' => $request['email'],
          'token' => $token
        );
        return $this->createJsonResult($result);
      } else {
        $result = "Password is incorrect.";
        return $this->createJsonResult($result);
      }
    }
  }

  public function register(Request $request)
  {
    $toArrayPreferedGenres = explode(",", $request->PreferedGenres);
    $validator = Validator::make(
      $request->all(),
      [
        'username'     => 'required|string|between:2,100',
        'name'     => 'required|string|between:2,100',
        'email'    => 'required|email|unique:users',
        'password' => 'required|string|min:6',
        'SocialMedia' => 'sometimes|string|nullable',
        // 'gender' => 'required|string',
        'urlAvatar' => 'sometimes|string|nullable',
        'created_at' => 'required|date',
        'updated_at' => 'required|date'

      ]
    );
    if ($validator->fails()) {
      return response()->json(
        [$validator->errors()],
        422
      );
    }
    // //Check validate Gender
    // $gender = $request->gender;
    // if ($gender != 'Male' && $gender != 'Female' && $gender != 'Non-binary') {
    //   return response()->json(
    //     "Gender not correct",
    //     422
    //   );
    // }
    //Check SocialMedia null
    $createUser = DB::table('Moderators')
      ->insert([
        'username' => $request['username'],
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
        'SocialMedia' =>  $request['SocialMedia'],
        // 'gender' =>  $request['gender'],
        'urlAvatar' => $request['urlAvatar'],
        'created_at' => $request['created_at'],
        'updated_at' => $request['updated_at']
      ]);
    return $this->createJsonResult($createUser);
  }
}
