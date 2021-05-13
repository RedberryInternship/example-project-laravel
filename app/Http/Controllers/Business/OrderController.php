<?php

namespace App\Http\Controllers\Business;

use App\Order;
use Illuminate\Http\Response;
use App\Library\Entities\Helper;
use App\Http\Controllers\Controller;
use App\Library\Interactors\Exporter;
use App\Library\Interactors\Business\Orders;

class OrderController extends Controller
{
    private $numbersPerPage = 200;

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
        $this->showGate($id);
        return Orders :: getInfo($id);
    }

    /**
     * Check if user is permitted to be here.
     * 
     * @param int $id
     * @return void
     */
    public function showGate($id)
    {
        $order = Order::findOrFail($id);
        $charger = $order->getCharger();
        
        $chargerCompanyID = (int) $charger -> company_id;
        $userCompanyID = (int) auth() -> user() -> company_id;

        abort_if($chargerCompanyID !== $userCompanyID, Response::HTTP_FORBIDDEN);
    }
}
