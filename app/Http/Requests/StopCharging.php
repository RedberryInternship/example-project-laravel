<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\ValidatorCustomJsonResponse as Response;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Http\Resources\Order as OrderResource;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Library\Entities\Helper;
use App\Facades\Simulator;
use App\Facades\Charger;
use App\Traits\Message;
use App\Order;

class StopCharging extends FormRequest
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
            'order_id' => [
                'bail',
                'required',
                'integer',
                'exists:orders,id',
            ],
        ];
    }

    public function messages()
    {
        return [
            'order_id.required' => 'order_id is required.',
            'order_id.integer'  => 'order_id must be integer.',
            'order_id.exists'   => 'Such order doesn\'t exists in db.',
        ];
    }

    public function withValidator( $validator )
    {
        $this -> respond( $validator, 422, $this -> messages [ 'something_went_wrong' ] );
    }

    /**
     * Order instance.
     * 
     * @var Order
     */
    protected function passedValidation()
    {
        $orderId        = request() -> get( 'order_id' );

        $this -> order  = Order :: with(
            [
                'charger_connector_type.charger',
                'charger_connector_type.connector_type',
                'user',
            ]
        ) -> find( $orderId );
    }

    /**
     * Stop charging process.
     * 
     * @return void
     */
    public function stopChargingProcess()
    {
        $charger        = $this -> order -> charger_connector_type -> charger;
        $transactionID  = $this -> order -> charger_transaction_id;
        
        Charger::stop( 
            $charger -> charger_id, 
            $transactionID,
        );

        # GLITCH
        if(Helper :: isDev())
        {
            if( $this -> order -> charger_connector_type -> isChargerFast() )
            {
                Simulator :: plugOffCable( $charger -> charger_id );
            }
        }
    }

    /**
     * Update charging status.
     * 
     * @return void
     */
    public function updateChargingStatus()
    {
        $this -> order -> charger_connector_type -> isChargerFast()
            ? $this -> order -> updateChargingStatus( OrderStatusEnum :: FINISHED )
            : $this -> order -> updateChargingStatus( OrderStatusEnum :: CHARGED  );
    }

    /**
     * Determine if charging type is
     * by amount.
     * 
     * @return bool
     */
    private function isChargingTypeByAmount()
    {
      return $this -> order -> charging_type == ChargingTypeEnum :: BY_AMOUNT;  
    }

    /**
     * Build and return order resource.
     * 
     * @return OrderResource
     */
    public function buildResource()
    {
        $this -> order -> finished = true;
        $resource = new OrderResource( $this -> order );
    
        return $resource;
    }
}
