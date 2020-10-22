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
   * Relationship with charger.
   */
  public function charger()
  {
    return $this -> belongsTo(Charger :: class);
  }
}