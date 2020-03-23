<?php
namespace App\Library;
use App\ChargerType;
use App\Tag;

class Charger
{
	public function getChargerArray($chargers)
	{
		$chargers_array = [];
    	foreach($chargers as $charger)
    	{
    		$tags_array = [];
    		if($charger -> tags)
    		{
	    		foreach($charger -> tags as $tag)
	    		{
	    			$tags_array[] = array(
	    				'id'   => $tag -> id,
	    				'name' =>  $tag -> name
	    			);
	    		}
    		}
    		$charger_attributes = [];
    		$connector_types = $charger -> connector_types;
    		if($connector_types)
    		{
    			foreach($connector_types as $connector_type)
    			{
    				$charger_type = ChargerType::where('id',$connector_type -> pivot -> charger_type_id) -> first();
    				$charger_attributes[] = array(
    					'connector_type_id'  => $connector_type -> id,
    					'connector_type_name' => $connector_type -> name,
    					'charger_type_id'	 => $connector_type -> pivot -> charger_type_id,
    					'charger_type_name'	 => $charger_type 	-> name
    				);
    			}
    		}
    		$chargers_array[] = array(
    			'id' 		  			=> $charger -> id,
    			'old_id'      			=> $charger -> old_id,
    			'name'	      			=> array(
    				'ka'	=> array_key_exists('ka',$charger -> translations['name']) ? $charger -> translations['name']['ka'] : '',
    				'en'	=> array_key_exists('en',$charger -> translations['name']) ? $charger -> translations['name']['en'] : '',
    				'ru'	=> array_key_exists('ru',$charger -> translations['name']) ? $charger -> translations['name']['ru'] : '',
    			),
    			'charger_attributes' 	=> $charger_attributes,
    			'tags_array'			=> $tags_array,
    			'code' 	      			=> $charger -> code,
    			'description' 			=> array(
    				'ka'	=> array_key_exists('ka',$charger -> translations['description']) ? $charger -> translations['description']['ka'] : '',
    				'en'	=> array_key_exists('en',$charger -> translations['description']) ? $charger -> translations['description']['en'] : '',
    				'ru'	=> array_key_exists('ru',$charger -> translations['description']) ? $charger -> translations['description']['ru'] : '',
    			),
    			'user_id'     			=> $charger -> user_id,
    			'location'    			=> array(
    				'ka'	=> array_key_exists('ka',$charger -> translations['location']) ? $charger -> translations['location']['ka'] : '',
    				'en'	=> array_key_exists('en',$charger -> translations['location']) ? $charger -> translations['location']['en'] : '',
    				'ru'	=> array_key_exists('ru',$charger -> translations['location']) ? $charger -> translations['location']['ru'] : '',
    			),
          'public' 	  			=> $charger -> public,
    			'active'	  			=> $charger -> active,
    			'lat'		  			=> $charger -> lat,
    			'lng'		  			=> $charger -> lng,
    			'charger_group_id' 		=> $charger -> charger_group_id,
    			'iban'					=> $charger -> iban,
    			'last_update'			=> $charger -> last_update

    		);
    	}
		return [
            'chargers_array'  => $chargers_array
        ];
	}
}

