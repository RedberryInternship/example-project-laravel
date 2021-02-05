<?php

namespace Tests\Unit;

use App\Contact as AppContact;
use Tests\TestCase;

class ContactInformation extends TestCase {
  /**
   * Setup sample.
   */
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> url = $this -> uri . 'contact';
  }
  
  /** @test */
  public function ok(): void 
  {
    factory(AppContact :: class) -> create();

    $this -> get($this -> url) -> assertOk();
  }
  
  /** @test */
  public function isNotOk(): void 
  {
    $this -> get($this -> url) -> assertStatus(404);
  }
  
  /** @test */
  public function structure(): void 
  {
    factory(AppContact :: class) -> create();
    $this -> get($this -> url) -> assertJsonStructure(
      [
        'address',
        'phone',
        'email',
        'fb_page',
        'fb_page_url',
        'web_page',
        'web_page_url',
      ]
    );
  }
}