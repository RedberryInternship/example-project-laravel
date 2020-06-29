<?php

namespace App\Exports;

use Auth;
use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        $orders = Order::whereHas('charger_connector_type.charger.user', function($query) use ($user) {
            $query -> where('users.id', $user -> id);
        }) -> with([
            'user',
            'payments',
            'user_card',
            'charger_connector_type.charger'
        ]) -> orderBy(
            'id', 'DESC'
        ) -> get();

        return collect(
            self::generateData($orders)
        );
    }

    /**
     * Generate Excel Data.
     * 
     * @param Order $orders
     */
    protected static function generateData($orders)
    {
        $data[] = [
            'ID',
            'დამტენი',
            'მომხმარებელი',
            'ბარათი',
            'გადახდები',
            'დრო',
            'დასრულებული'
        ];

        foreach ($orders as $order)
        {
            $payments      = '';
            $paymentsCount = $order -> payments -> count();
            foreach ($order -> payments as $index => $payment)
            {
                $payments .= $payment -> type . ': ' . $payment -> price;

                if ($paymentsCount != $index + 1)
                {
                    $payments .= "\n";
                }
            }

            $data[] = [
                $order -> id,
                $order -> charger_connector_type -> charger -> location,
                $order -> user ? $order -> user -> first_name . ' ' . $order -> user -> last_name : '-',
                $order -> user_card ? $order -> user_card -> masked_pan : '-',
                $payments,
                $order -> created_at -> format('d-m-Y H:i'),
                $order -> charging_status == 'FINISHED' ? 'კი' : 'არა'
            ];
        }

        return $data;
    }
}
