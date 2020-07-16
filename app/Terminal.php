<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
  /**
   * Laravel guarded attribute.
   * 
   * @var array $guarded
   */
  protected $guarded = [];

  /**
   * belongsTo relationship with ChargerConnectorType.
   * 
   * @return @return Illuminate\Database\Eloquent\Collection
   */
  public function charger_connector_type()
  {
    return $this -> hasOne( ChargerConnectorType :: class );
  }
}