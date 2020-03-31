<?php

namespace App\Traits\Testing;
use App\User as UserModel;

trait User{


  public function createUserAndReturnToken($phone_number = '+995591935080', $password = '+995591935080')
  {
    factory(UserModel::class)->create([
      'phone_number' => $phone_number,
      'password' => bcrypt($password),
    ]);

    $response = $this -> post('/api/app/V1/login',[
      'phone_number' => $phone_number,
      'password' => $password,
    ]);

    $token= $response -> decodeResponseJson()['access_token'];

    return $token;
  }

}