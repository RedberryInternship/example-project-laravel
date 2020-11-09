<?php

namespace App\Http\Controllers\api\app\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Favorite;
use App\Charger;
use App\User;

class FavoriteController extends Controller
{
    public function postAddFavorite(Request $request)
    {
    	$chargerId 	= $request -> charger_id;
			$user				= User :: find( auth() -> user -> id );
    	$status     = 400;
			$favorite   = Favorite :: where( 'user_id', $user -> id )
				-> where( 'charger_id', $chargerId ) 
				-> first();

    	if (is_null($favorite))
    	{
    		$user -> favorites() -> attach($chargerId);
    		$status = 200;
    	}

    	return response() -> json(['status' => $status], $status);
    }

    public function postRemoveFavorite(Request $request)
    {
    	$user    		= User :: find( auth() -> user() -> id );
    	$chargerId 	= $request -> charger_id;
			$favorite   = Favorite :: where( 'user_id', $user -> id ) 
				-> where( 'charger_id', $chargerId ) 
				-> first();

    	$status			= 400;

    	if ($favorite)
    	{
    		$user -> favorites() -> detach($chargerId);
    		$status = 200;
    	}

    	return response() -> json(['status' => $status], $status);
    }

    public function getUserFavorites()
    {
		$user = auth('api') -> user();

		$favoriteChargers = $user -> favorites;

		Charger::addIsFreeAttributeToCharger($favoriteChargers);
		
		return response() -> json(['user_favorite_chargers' => $favoriteChargers]);
    }
}
