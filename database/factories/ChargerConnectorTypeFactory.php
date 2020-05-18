<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\ChargerConnectorType;
use App\Charger;
use App\ConnectorType;


if( ConnectorType :: count() == 0 )
    {
      factory( ConnectorType :: class ) -> create([ 'name' => 'Type 2' ]);
      factory( ConnectorType :: class ) -> create([ 'name' => 'Combo 2' ]);
      factory( ConnectorType :: class ) -> create([ 'name' => 'CHAdeMO' ]);
    }

    $connectorType = ConnectorType :: inRandomOrder() -> first();



$factory->define(ChargerConnectorType::class, function (Faker $faker) use ( $connectorType ) {

    return [
      'charger_id'          => 0,
      'connector_type_id'   => $connectorType -> id,
      'm_connector_type_id' => $faker -> numberBetween(1,2),
      'max_price'           => $faker -> randomNumber(),
      'min_price'           => $faker -> randomNumber(),
    ];
});

$factory -> afterCreating( ChargerConnectorType :: class, function($chargerConnectorType, $faker){
  if( ! $chargerConnectorType -> charger )
  {
    $chargerConnectorType -> charger_id = factory( Charger::class ) -> create() -> id;
    $chargerConnectorType -> save();
  }
});