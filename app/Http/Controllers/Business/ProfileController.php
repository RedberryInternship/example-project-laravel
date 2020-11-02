<?php

namespace App\Http\Controllers\Business;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

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
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return View
     */
    public function store(Request $request)
    {
        $userId = auth() -> user() -> id;
        $user   = User :: find($userId);

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
     * Download contract file.
     * 
     * @return File
     */
    public static function downloadContractFile()
    {
        $userId           = auth() -> user() -> id;
        $user             = User :: with( 'company' ) -> find( $userId );
        $contractFilePath = $user -> company -> contract_file;

        $ext = explode('.', $contractFilePath);
        $ext = end($ext);

        return Storage :: disk('public') -> download( $contractFilePath, 'contract.' . $ext );
    }
}
