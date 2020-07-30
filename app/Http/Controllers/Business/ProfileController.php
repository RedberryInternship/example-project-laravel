<?php

namespace App\Http\Controllers\Business;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * ProfileController Constructor. 
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

        return view('business.profile.index') -> with([
            'user'           => $user,
            'tabTitle'       => 'პროფილი',
            'activeMenuItem' => 'profile'
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
        $user = Auth::user();

        $rules = [
            'first_name'   => 'required',
            'phone_number' => 'required',
            'email'        => 'email|required'
        ];

        if ($request -> get('password'))
        {
            $rules['password'] = 'required|confirmed|min:6';
        }

        $request -> validate($rules);

        $data = $request -> except([
            '_token',
            'password',
            'password_confirmation'
        ]);

        if ($request -> get('password'))
        {
            $data = $request -> merge([
                'password' => bcrypt($request -> get('password'))
            ]) -> except([
                '_token',
                'password_confirmation'
            ]);
        }

        $user -> update($data);

        return redirect() -> back();
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