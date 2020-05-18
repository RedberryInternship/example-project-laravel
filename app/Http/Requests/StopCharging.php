<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Traits\ValidatorCustomJsonResponse as Response;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Http\Resources\Order as OrderResource;
use App\Enums\OrderStatus as OrderStatusEnum;
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
    }

    /**
     * Update charging status.
     * 
     * @return void
     */
    public function updateChargingStatus()
    {
        $order = $this -> order;
        if( $this -> isChargingTypeByAmount() )
        {
            $order -> charging_status = OrderStatusEnum :: USED_UP;
        }
        else
        {
            $order -> charging_status = OrderStatusEnum :: CHARGED;
        }

        $order -> save();
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
        $resource = new OrderResource( $this -> order );
        $resource -> setAdditionalData(
          [
            'message' => $this -> messages [ 'charging_successfully_finished' ],
          ]
        );

        return $resource;
    }
}
