<?php

namespace App\Http\Controllers\api\app\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Favorite;
use App\Charger;

class FavoriteController extends Controller
{
    public function postAddFavorite(Request $request)
    {
    	$charger_id = $request -> charger_id;
    	$user       = auth('api') -> user();
    	$user_id 	= $user -> id;
    	$charger    = Charger::where('id', $charger_id) -> first();
    	$status     = 400;
    	$favorite   = Favorite::where([['user_id', $user_id],['charger_id', $charger_id]]) -> first();

    	if(is_null($favorite))
    	{
    		$user->favorites()->attach($charger_id);
    		$status = 200;
    	}

    	return response() -> json(['status' => $status], $status);
    }

    public function postRemoveFavotite(Request $request)
    {
    	$user    	= auth('api') -> user();
    	$user_id 	= $user -> id;
    	$charger_id = $request -> charger_id;
    	$favorite   = Favorite::where([['user_id', $user_id],['charger_id', $charger_id]]) -> first();
    	$status		= 400;

    	if($favorite)
    	{
    		$user->favorites()->detach($charger_id);
    		$status = 200;
    	}

    	return response() -> json(['status' => $status], $status);
    }

    public function getUserFavorites()
    {
			$user  	  				 = auth('api') -> user();
			$favorite_chargers = $user -> favorites;

      Charger::addIsFreeAttributeToChargers($favorite_chargers);
			
			return response() -> json(['user_favorite_chargers' => $favorite_chargers]);
    }
}
