<?php

namespace App\Http\Controllers\Business;

use App\Http\Requests\Business\Profile\UpdateInfo;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $userId = auth() -> user() -> id;
        $user   = User :: with( 'company' ) -> find($userId);

        return view('business.profile.index') -> with(
            [
                'user'           => $user,
                'company'        => $user -> company,
                'companyName'    => $user -> company -> companyName,
                'tabTitle'       => 'პროფილი',
                'activeMenuItem' => 'profile',
                'companyName'    => $user -> company -> name,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return View
     */
    public function store(UpdateInfo $request)
    {
        $userId = auth() -> id();
        $user   = User :: find($userId);

        $data = [];
        $request -> has( 'first_name'   ) && $data[ 'first_name'    ]= $request -> get('first_name');
        $request -> has( 'phone_number' ) && $data[ 'phone_number'  ]= $request -> get('phone_number');
        $request -> has( 'email'        ) && $data[ 'email'         ]= $request -> get('email');
        $request -> has( 'password'     ) && $data[ 'password'      ]= bcrypt( $request -> get('password') );

        $user -> update($data);
        return redirect() -> back();
    }

    /**
     * Download contract file.
     * 
     * @return File
     */
    public function downloadContractFile()
    {
        $userId           = auth() -> user() -> id;
        $user             = User :: with( 'company' ) -> find( $userId );
        $contractFilePath = $user -> company -> contract_file;

        $ext = explode('.', $contractFilePath);
        $ext = end($ext);

        return Storage :: disk('public') -> download( $contractFilePath, 'contract.' . $ext );
    }
}
