<?php

namespace App\Http\Controllers\Business\Exports;

use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * OrderController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Download Orders.
     */
    public function __invoke()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}
