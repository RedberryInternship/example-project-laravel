<?php

namespace Tests\Unit;

use App\FAQ as AppFAQ;
use Tests\TestCase;

class FAQ extends TestCase {
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> url = $this -> uri . 'faq';
    factory( AppFAQ :: class, 10 ) -> create();
  }

  /** @test */
  public function is_ok(): void
  {
    $this -> get( $this -> url ) -> assertOk();
  }

  /** @test */
  public function faq_structure(): void
  {
    $this -> get( $this -> url ) -> assertJsonStructure(
      [
        'faq' => [
          '*' => [
            "question" => [
              "en",
              "ka",
              "ru",
            ],
            "answer" => [
                "en",
                "ka",
                "ru",
            ],
            'created_at',
            'updated_at',
          ]
        ]
      ]
    );
  }
}