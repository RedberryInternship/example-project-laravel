<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\Order;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
