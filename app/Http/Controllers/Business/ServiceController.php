<?php

namespace App\Http\Controllers\Business;

use App\BusinessService;
use App\Helpers\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ServiceController extends Controller
{
    /**
     * ChargerController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('business.auth');

        View::share([
            'languages' => Language::all()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user -> load(['business_services' => function($query) {
            $query -> orderBy('id', 'DESC');
        }]);

        return view('business.services.index') -> with([
            'user'           => $user,
            'activeMenuItem' => 'charger_services',
            'tabTitle'       => 'დამატებითი სერვისები',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('business.services.edit') -> with([
            'activeMenuItem' => 'charger_services',
            'tabTitle'       => 'დამატებითი სერვისები',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user     = Auth::user();
        $service  = BusinessService::create([
            'user_id' => $user -> id,
            'image'   => ''
        ]);

        $languages = Language::all();
        foreach ($languages as $language)
        {
            $service -> setTranslation('title', $language, $request -> get('title_' . $language));
            $service -> setTranslation('description', $language, $request -> get('description_' . $language));
        }

        $service -> save();

        return redirect('/business/services');
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
        $user    = Auth::user();
        $service = BusinessService::find($id);

        if ($service -> user_id != $user -> id)
        {
            abort(403, 'Unauthorized action.');
        }

        return view('business.services.edit') -> with([
            'user'           => $user,
            'service'        => $service,
            'activeMenuItem' => 'charger_services',
            'tabTitle'       => 'დამატებითი სერვისები',
        ]);
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
        $user     = Auth::user();
        $service  = BusinessService::find($id);

        if ($service -> user_id != $user -> id)
        {
            abort(403, 'Unauthorized action.');
        }

        $languages = Language::all();
        foreach ($languages as $language)
        {
            $service -> setTranslation('title', $language, $request -> get('title_' . $language));
            $service -> setTranslation('description', $language, $request -> get('description_' . $language));
        }

        $service -> save();

        return redirect('/business/services');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user     = Auth::user();
        $service  = BusinessService::find($id);

        if ($service -> user_id != $user -> id)
        {
            abort(403, 'Unauthorized action.');
        }

        $service -> delete();

        return redirect('/business/services');
    }
}
