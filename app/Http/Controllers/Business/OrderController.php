<?php

namespace App\Http\Controllers\Business;

use App\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Library\Interactors\Exporter;

class OrderController extends Controller
{
    private $numbersPerPage = 200;

    /**
     * OrderController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $orders = Order::whereHas('charger_connector_type.charger', function($query) use ($user) {
            $query -> whereNotNull('chargers.company_id');
            $query -> where('chargers.company_id', $user -> company_id);
        }) -> with([
            'payments',
            'user_card',
            'charger_connector_type.charger'
        ]) -> orderBy(
            'id', 'DESC'
        ) -> paginate($this -> numbersPerPage);

        return view('business.orders.index') -> with([
            'user'           => $user,
            'orders'         => $orders,
            'tabTitle'       => 'ტრანზაქციები',
            'activeMenuItem' => 'orders'
        ]);
    }

    /**
     * Download business transactions report.
     * 
     * @return \File
     */
    public function downloadExcel()
    {
        return Exporter :: exportBusinessOrders();
    }
}
