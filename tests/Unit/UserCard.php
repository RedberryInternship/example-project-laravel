<?php

namespace Test\Unit;

use App\UserCard as AppUserCard;
use Tests\TestCase;

class UserCard extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> getSaveCardURL = $this -> uri . 'save-card-url';
    $this -> setDefaultCardURL = $this -> uri . 'user-card/set-default';
    $this -> removeCardURL = $this -> uri . 'user-card/remove-card';

    $this -> user = $this -> createUser();
    $this -> userCards = factory(AppUserCard::class, 3)->create(
      [
        'user_id' => $this -> user -> id,
        'default' => false,
      ]
    );
  }

  /** @test */
  public function get_save_card_url_ok(): void
  {
    $this
      -> actAs($this->user)
      -> get($this->getSaveCardURL)
      -> assertOk();
  }

  /** @test */
  public function set_default_user_card_ok(): void
  {
    $this
      -> actAs($this->user)
      -> post(
        $this -> setDefaultCardURL,
        [ 
          'user_card_id' => $this -> user -> user_cards -> first() -> id 
        ],
        [
          'Accept' => 'application/json',
        ]
      ) 
      -> assertOk();
  }
  
  /** @test */
  public function set_default_user_card_has_validation_errors(): void
  {
    $this
      -> actAs($this->user)
      -> post(
        $this -> setDefaultCardURL,
        [ 
          // 'user_card_id' => $this -> user -> user_cards -> first() -> id 
        ],
        [
          'Accept' => 'application/json',
        ]
      ) 
      -> assertJsonValidationErrors(['user_card_id']);
  }

  /** @test */
  public function remove_card_ok(): void
  {
    $userCardsCount = $this -> user -> user_cards -> count();

    $this
      -> actAs($this->user)
      -> post(
        $this -> removeCardURL,
        [
          'user_card_id' => $this -> user -> user_cards -> first() -> id,
        ],
        [
          'Accept' => 'application/json',
        ]
      )
      -> assertOk();
    
    $this -> user -> refresh();
    
    $this
      -> assertCount(
        $userCardsCount - 1, 
        $this -> user -> user_cards,
      );
  }
  
  /** @test */
  public function remove_card_has_validation_errors(): void
  {
    $this
      -> actAs($this->user)
      -> post(
        $this -> removeCardURL,
        [
          // 'user_card_id' => $this -> user -> user_cards -> first() -> id,
        ],
        [
          'Accept' => 'application/json',
        ]
      )
      -> assertJsonValidationErrors(['user_card_id']);
  }
}