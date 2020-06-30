<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidatorCustomJsonResponse as Response;
use App\Traits\Message;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;

use App\Rules\ModelHasRelation;
use App\ChargerConnectorType;
use App\Rules\BusyCharger;
use App\Facades\Charger;
use App\Order;


class StartCharging extends FormRequest
{
    use Response,
        Message;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'charger_connector_type_id' => [
                'bail',
                'required',
                'integer',
                'exists:charger_connector_types,id',
                new ModelHasRelation( ChargerConnectorType::class, 'charger'),
                new ModelHasRelation( ChargerConnectorType::class, 'connector_type'),
                new BusyCharger(),
            ],
            'charging_type'             => [
                'required',
                'string',
                'in:BY_AMOUNT,FULL_CHARGE',
            ],
            'price'                     => [
                'required_if:charging_type,BY_AMOUNT',
                'numeric',
            ],
            'user_card_id'              => [
                'required',
                'exists:user_cards,id',
            ] 
        ];
    }

    public function messages()
    {
        return [
            'charger_connector_type_id.required' => 'charger_connector_type_id is required',
            'charger_connector_type_id.integer'  => 'charger_connector_type_id must be integer.',
            'charger_connector_type_id.exists'   => 'Such charger connector type doesn\'t exists in db.',

            'charging_type.required'             => 'Charging Type is required.',
            'charging_type.string'               => 'Charging Type should be string.',
            'charging_type.in'                   => 'Charging Type should be BY_AMOUNT or FULL_CHARGE.',
            
            'price.required_if'                  => 'Price field is required.',
            'price.numeric'                      => 'Price must be numeric.',

            'user_card_id'                       => 'UserCard with such user_card_id doesn\'t exists in db.'
        ];
    }

    public function withValidator($validator)
    {
        $chargerIsFree = $this -> isChargerFree( $validator );
        
        if( ! $chargerIsFree )
        {
            $this -> respond($validator, 400, $this -> messages [ 'charger_is_not_free' ]);
        }
        else
        {
            $this -> respond($validator, 422, $this -> messages [ 'something_went_wrong' ]);
        }
    }

    private function isChargerFree($validator)
    {
        $data = $validator -> getData();
        
        if( isset( $data[ 'charger_connector_type_id' ]))
        {
            $chargerConnectorTypeId = $data [ 'charger_connector_type_id' ];
            $busyCharger            = new BusyCharger();
        
            return $busyCharger -> passes( null, $chargerConnectorTypeId );
        }
        else
        {
            return false;
        }
    }

    /**
     * ChargerConnectorType instance.
     * 
     * @var ChargerConnectorType
     */
    private $chargerConnectorType;

    /**
     * Order instance.
     * 
     * @var Order
     */
    private $order;

    /**
     * Charger transaction id.
     * 
     * @var string
     */
    private $transactionID;

    /**
     * If validation passes.
     * 
     * @return void
     */
    protected function passedValidation()
    {
        $chargerConnectorTypeId = request() -> get('charger_connector_type_id');
        $this -> chargerConnectorType = ChargerConnectorType::find( $chargerConnectorTypeId );
    }

    /**
     * Get ChargerConnectorType from request.
     * 
     * @return ChargerConnectorType
     */
    public function getChargerConnectorType()
    {
        return $this -> chargerConnectorType;
    }

    /**
     * Determine if charger is fast.
     * 
     * @return bool
     */
    public function isChargerFast()
    {
        $chargerConnectorType = $this -> chargerConnectorType;
        $chargerType          = $chargerConnectorType -> determineChargerType();

        return ChargerTypeEnum :: FAST == $chargerType;
    }

    /**
     * Determine if charging type is BY_AMOUNT.
     * 
     * @return bool
     */
    public function isChargingTypeByAmount()
    {
        $chargingType = request() -> get( 'charging_type' );

        return $chargingType == ChargingTypeEnum :: BY_AMOUNT;
    }

    /**
     * Start charging process.
     * 
     * @return void
     */
    public function startChargingProcess()
    {
        $chargerConnectorType = $this -> getChargerConnectorType();

        $transactionID = Charger::start(
            $chargerConnectorType   -> charger -> charger_id, 
            $chargerConnectorType   -> m_connector_type_id
        );

        $this -> transactionID = $transactionID;
    }

    /**
     * Create order.
     * 
     * @param  string $transactionID
     * @return Order
     */
    public function createOrder()
    {
        $chargerConnectorTypeId = request() -> get( 'charger_connector_type_id' );
        $chargingType           = request() -> get( 'charging_type' );
        $userCardId             = request() -> get( 'user_card_id' );
        $targetPrice            = $this -> isChargingTypeByAmount() ? request() -> get( 'price' ) : null;
        $chargingStatus         = $this -> isChargerFast() 
            ? OrderStatusEnum :: CHARGING 
            : OrderStatusEnum :: INITIATED;

        $order = Order::create(
            [
                'charger_connector_type_id' => $chargerConnectorTypeId,
                'charger_transaction_id'    => $this -> transactionID,
                'charging_status'           => $chargingStatus,
                'user_card_id'              => $userCardId,
                'user_id'                   => auth() -> user() -> id,
                'charging_type'             => $chargingType,
                'target_price'              => $targetPrice,
            ]
        );
        
        $this -> order = $order;

        return $order;
    }

    /**
     * Make first payment transaction on fast charger.
     * 
     * @param  Order $order
     * @return void
     */
    public function pay()
    {
        if( $this -> isChargingTypeByAmount() )
        {
            $targetPrice = $this -> order -> target_price;

            $this -> order -> pay( PaymentTypeEnum :: CUT, $targetPrice );
        }
        else
        {
            $this -> order -> pay( PaymentTypeEnum :: CUT, 20 );
        }
    }

    /**
     * Create kilowatt record.
     * 
     * @param   Order $order
     * @return  void
     */
    public function createKilowattRecord()
    {
        $this -> order -> kilowatt() -> create([ 'consumed' => 0 ]);
    }
}
