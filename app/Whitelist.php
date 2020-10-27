<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model {
  /**
   * Laravel guarded attribute.
   * 
   * @var array
   */
  protected $guarded = [];

  /**
   * Disable laravel`s timestamps.
   * 
   * @var boolean
   */
  public $timestamps = false;

  /**
   * Relationship with charger.
   */
  public function charger()
  {
    return $this -> belongsTo(Charger :: class);
  }
}