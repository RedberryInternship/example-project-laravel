<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Order;
use App\ChargerConnectorType;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\ChargingType as ChargingTypeEnum;


$factory->define( Order :: class, function (Faker $faker) {
return [
        'user_id'                       => $faker -> unique( true ) -> randomNumber(),
        'charger_connector_type_id'     =>  0,
        'charging_type'                 => ChargingTypeEnum :: FULL_CHARGE,
        'charger_transaction_id'        => $faker -> unique( true ) -> randomNumber(5),
        'price'                         => $faker -> unique( true ) -> randomFloat(),
        'target_price'                  => $faker -> unique( true ) -> randomFloat(),
        'charging_status'               => OrderStatusEnum :: INITIATED,
        'charging_status_change_dates'  => [],
        'comment'                       => $faker -> sentence(),
    ];
});

$factory -> afterCreating( Order :: class, function( $order, $faker){
    $order -> load( 'charger_connector_type' );

    if( ! $order -> charger_connector_type )
    {
        $order -> charger_connector_type_id = factory( ChargerConnectorType :: class ) -> create() -> id;
        $order -> save();
    }
});