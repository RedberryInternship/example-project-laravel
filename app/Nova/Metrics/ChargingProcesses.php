<?php

namespace App\Nova\Metrics;

use Illuminate\Http\Request;
use App\Enums\OrderStatus as OrderStatusEnum;
use Laravel\Nova\Metrics\Partition;

use App\Order;

class ChargingProcesses extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $orders = Order :: active();
        return $this->count($request, $orders, 'charging_status') 
            -> colors(
                [
                    OrderStatusEnum :: INITIATED => '#f4c63d',
                    OrderStatusEnum :: CHARGING  => '#4caf50',
                    OrderStatusEnum :: CHARGED   => '#114814',
                    OrderStatusEnum :: USED_UP   => '#114814',
                    OrderStatusEnum :: ON_FINE   => '#f64747',
                    OrderStatusEnum :: ON_HOLD   => '#999',
                ]
            );
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'charging-processes';
    }
}
