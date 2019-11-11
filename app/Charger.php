<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Charger extends Model
{
    use HasTranslations;

    public $translatable = [
      'description', 
      'location',
      'name'
    ];

    protected $fillable = [
        'name',
        'charger_id',
        'code',
        'description',
        'user_id',
        'location',
        'public',
        'active',
        'lat',
        'lng',
        'old_id',
        'charger_group_id',
        'iban',
        'last_update'
    ];

    public function user()
   	{
   		return $this -> belongsTo('App\User');
   	}

   	public function types()
   	{
   		return $this -> belongsToMany('App\ChargerType', 'charger_charger_types');
   	}

    public function tags()
    {
      return $this -> belongsToMany('App\Tag', 'charger_tags');
    }

    public function connector_types()
    {
      return $this -> belongsToMany('App\ChargerChargerType', 'charger_types_connector_types');
    }

    public function chargerChargerTypes() {
      return $this -> hasMany('App\ChargerChargerType');
    }

}
