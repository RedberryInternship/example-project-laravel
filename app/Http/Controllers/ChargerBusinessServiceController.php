<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Facades\File;
use App\ChargerBusinessService;
use App\BusinessService;


class ChargerBusinessServiceController extends Controller
{
    public function getBusinessServices()
    {
        $user     		   = Auth::user();
        $business_services = BusinessService::OrderBy('id', 'desc') -> get();
        return view('business.business-services') -> with([
            'tabTitle'            		=> 'დამატებითი სერვისები',
            'activeMenuItem'      		=> 'charger_services',
            'business_services' 		=> $business_services,
            'user'                		=> $user    
        ]);
    }

    public function getAddBusinessService()
    {
    	$user 			   = Auth::user();
    	$business_services = BusinessService::OrderBy('id', 'desc') -> get();
    	return view('business.add-business-service') -> with([
            'tabTitle'            		=> 'დამატებითი სერვისები',
            'activeMenuItem'      		=> 'charger_services',
            'business_services' 		=> $business_services,
            'user'                		=> $user    
        ]);
    }

    public function postAddBusinessService(Request $request)
    {
    	$user 			   = Auth::user();

    	$business_service  = BusinessService::create([
    		'user_id'  		 => $user -> id,
    		'title_ka' 		 => $request->input('title_ge'),
    		'title_en' 		 => $request->input('title_en'),
    		'title_ru' 		 => $request->input('title_ru'),
    		'description_ka' => $request->input('description_ge'),
    		'description_en' => $request->input('description_ge'),
    		'description_ru' => $request->input('description_ge'),
    		'image' 		 => ''
    	]);

    	$path      = 'images/business-services/'.$user -> id.'/'.$business_service -> id;
		
		if(!File::exists($path)) {
		    File::makeDirectory($path, 0777,true);
		}

        $images = $request->file();

        $destinationPath = public_path($path);

        foreach ($images as $image) {
            $imagename = $user -> id.'-'.rand(10,100).'-'.time().'.'.$image->getClientOriginalExtension();
            $image->move($destinationPath, $imagename);
        }
        $business_service -> image = $imagename;
        $business_service -> save();

    	return redirect('/business/business-services');
    }

    public function getDeleteBusinessService($service_id)
    {	
    	$user = Auth::user();

    	BusinessService::where('id', $service_id) -> delete();
    	$directory = 'images/business-services/'.$user -> id.'/'.$service_id;
        File::deleteDirectory($directory); 

    	return redirect('/business/business-services');
    }
}
