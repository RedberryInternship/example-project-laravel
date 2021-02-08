<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportMarks;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportCarModels;

use App\UserCarModel;
use App\CarModel;

class UserCar extends TestCase 
{
  protected function setUp(): void
  {
    parent :: setUp();
    ImportMarks     :: execute();
    ImportCarModels :: execute();

    $this -> user = $this -> createUser();
    $this -> addCarsToUser( $this -> user -> id );

    $this -> getUrl     = $this -> uri . 'get-user-cars';
    $this -> addUrl     = $this -> uri . 'add-user-car';
    $this -> removeUrl  = $this -> uri . 'delete-user-car';
  }

  /** @test */
  public function get_user_cars_gives_ok(): void
  {
    $this 
      -> actAs( $this -> user )
      -> get( $this -> getUrl )
      -> assertOk();
  }
  
  /** @test */
  public function get_user_cars_gives_right_number_of_cars_models(): void
  {
    $this 
      -> actAs( $this -> user )
      -> get( $this -> getUrl )
      -> assertJsonCount(3, 'user_cars');
  }

  /** @test */
  public function add_user_car_gives_ok(): void
  {
    $userCarModelIds = $this -> user -> user_cars -> pluck( 'model_id' ) -> toArray();
    
    $newCarModel = CarModel :: whereNotIn( 'id', $userCarModelIds ) 
      -> inRandomOrder() 
      -> first();

    $this 
      -> actAs( $this -> user )
      -> post( $this -> addUrl, [
        'car_model_id' => $newCarModel -> id,
      ]) 
      -> assertOk();
    
    $this 
      -> actAs( $this -> user )
      -> get( $this -> getUrl )
      -> assertJsonCount(4, 'user_cars');
  }

  /** @test */
  public function remove_user_car_gives_ok(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> removeUrl, [
        'car_model_id' => $this -> user -> user_cars -> first() -> model_id,
      ])
      -> assertOk();
    
    $this 
      -> actAs( $this -> user )
      -> get( $this -> getUrl )
      -> assertJsonCount(2, 'user_cars');
  }

  private function addCarsToUser( $userId )
  {
    return CarModel :: inRandomOrder() 
      -> take( 3 ) 
      -> get()
      -> each( function ( $model ) use( $userId ) {
        UserCarModel :: create(
          [
            'user_id' => $userId,
            'model_id' => $model -> id,
          ]
        );
      });
  }
}
