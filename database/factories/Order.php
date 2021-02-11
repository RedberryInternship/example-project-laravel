<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use App\Order;
use App\ChargerConnectorType;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\UserCard;

$factory->define( Order :: class, function (Faker $faker) {
return [
        'user_id'                       => $faker -> randomNumber(),
        'charger_connector_type_id'     =>  0,
        'charging_type'                 => ChargingTypeEnum :: FULL_CHARGE,
        'charger_transaction_id'        => $faker -> randomNumber(5),
        'price'                         => $faker -> randomFloat(),
        'target_price'                  => $faker -> randomFloat(),
        'charging_status'               => OrderStatusEnum :: INITIATED,
        'charging_status_change_dates'  => [],
        'charger_name'                  => $faker -> userName,
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

    $order -> user_card_id = factory( UserCard :: class ) -> create(
        [
            'user_id' => $order -> user -> id,
        ]
    ) -> id;
    $order -> save();
});