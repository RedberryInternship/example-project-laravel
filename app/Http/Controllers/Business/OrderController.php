<?php

namespace App\Http\Controllers\Business;

use App\Order;
use App\Http\Controllers\Controller;
use App\Library\Entities\Helper;
use App\Library\Interactors\Exporter;
use App\Library\Interactors\Business\Orders;

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
     * @return View
     */
    public function index()
    {   
        $user   = auth() -> user();
        $orders = Order :: filterBusinessTransactions() -> paginate( $this -> numbersPerPage );

        return view('business.orders.index') -> with(
            [
                'orders'                 => $orders,
                'tabTitle'               => 'ტრანზაქციები',
                'activeMenuItem'         => 'orders',
                'user'                   => $user,
                'contractDownloadPath'   => Helper :: url('/business/order-exports', request() -> input()),
                'companyName'            => $user -> company -> name,
            ]
        );
    }

    /**
     * Download business transactions report.
     * 
     * @return File
     */
    public function downloadExcel()
    {
        $filteredOrderIds = Order :: filterBusinessTransactions() -> pluck('id') -> toArray();
        return Exporter :: exportBusinessOrders( $filteredOrderIds );
    }

    /**
     * Get specific transaction.
     * 
     * @return JSON
     */
    public function show($id) 
    {
        return Orders :: getInfo($id);
    }
}
