<?php

namespace Tests\Unit;

use App\Charger;
use Tests\TestCase;
use App\Favorite as AppFavorite;


class Favorite extends TestCase 
{
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> getUrl     = $this -> uri . 'user-favorites';
    $this -> addUrl     = $this -> uri . 'add-favorite';
    $this -> removeUrl  = $this -> uri . 'remove-favorite';

    $this -> user = $this -> createUser(
      [
        'phone_number' => '+995591215163',
        'password'     => bcrypt('gangeba_movide'),
      ]
    );

    $this -> createUserChargers( $this -> user -> id );

    $this -> nonFavoriteCharger = $this -> createNonFavoriteCharger();
  }

  /** @test */
  public function retrieve_favorites_is_ok(): void
  {
    $this 
      -> actAs($this -> user) 
      -> get( $this -> getUrl )
      -> assertOk();
  }

  /** @test */
  public function add_favorite_charger_gives_ok(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> addUrl, [
        'charger_id' => $this -> nonFavoriteCharger -> id,
      ])
      -> assertOk();

    $response = $this
      -> actAs( $this -> user )
      -> get( $this -> getUrl ) 
      -> decodeResponseJson( 'user_favorite_chargers' );

    $this -> assertCount( 4, $response );
  }

  /** @test */
  public function add_favorite_charger_has_validation_errors(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> addUrl, [])
      -> assertSessionHasErrors(['charger_id']);
  }

  /** @test */
  public function remove_favorite_charger_gives_ok(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> removeUrl, [
        'charger_id' => $this -> user -> favorites -> first() -> id,
      ])
      -> assertOk();

    $response = $this
      -> actAs( $this -> user )
      -> get( $this -> getUrl )
      -> decodeResponseJson( 'user_favorite_chargers' );

    $this -> assertCount( 2, $response );
  }

  /** @test */
  public function remove_favorite_charger_has_validation_errors(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> removeUrl, [], ['Accept' => 'application/json'])
      -> assertJsonValidationErrors(['charger_id']);
  }


  /** --- > helper functions < --- ** */

  private function createUserChargers($userId)
  {
    $chargers = factory( Charger :: class, 3 ) -> create();

    $chargers -> each( function ( $charger ) use( $userId ) {
      AppFavorite :: create(
        [
          'user_id'     => $userId,
          'charger_id'  => $charger -> id,
        ]
      );
    });
  }

  private function createNonFavoriteCharger()
  {
    return factory( Charger :: class ) -> create();
  }
}