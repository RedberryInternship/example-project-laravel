<?php

namespace App\Library\Testing;

use Faker\Generator as Faker;

class MishasMockCharger
{

  /**
   * Misha's Charger Attributes for Mock instance
   */
  public  $id, 
          $latitude,
          $longitude,
          $status,
          $type,
          $category,
          $description,
          $code,
          $paid,
          $connectors,
          $enabled;

  
  public function __construct(Faker $faker)
  {
    $this -> id = random_int(1, 300);
    $this -> latitude = $faker -> randomFloat(5, 0, 180);
    $this -> longitude = $faker -> randomFloat(5, 0, 180);
    $this -> status = random_int(0,1) ? '0-ON_LINE 1-CHARGING' : -1 ;
    $this -> type = random_int(0,1);
    $this -> category = random_int(0,1);
    $this -> description = $faker -> sentence(5);
    $this -> code = (string) $faker -> randomNumber(4);
    $this -> paid = random_int(0,1) ? true : false;
    $this -> enabled = random_int(0,1) ? true : false;

    $this -> connectors = [];
    if(random_int(0,3)){
      
      $type2 = [
        'id' => 1,
        'type' => 'Type 2',
      ];

      $this -> connectors []= (object) $type2;
    }
    else{
      
      $combo2 = [
        'id' => 1,
        'type' => 'Combo 2',
      ];
    
      $CHAdeMO = [
        'id' => 2,
        'type' => 'CHAdeMO',
      ];

      $this -> connectors []= (object) $combo2;
      $this -> connectors []= (object) $CHAdeMO;
    } 
  }
}