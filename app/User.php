<?php

namespace App;

use Twilio;
use App\Favorite;
use App\Enums\OrderStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Casting fields to into another type.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function getJWTIdentifier()
    {
        return $this -> getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Build User's response array.
     * 
     * @param $token
     */
    public static function respondWithToken($token)
    {
        $user = auth('api') -> user();

        $user -> load('user_cards','user_cars','car_models');

        return [
            'user'          => $user,
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth('api') -> factory() -> getTTL()
        ];
    }

    /**
     * Send SMS.
     * 
     * @param $phoneNumber
     * @param $message
     * 
     * @return boolean
     */
    public static function sendSms($phoneNumber, $message)
    {
        if ( ! $phoneNumber)
        {
            return false;
        }

        $phoneNumber = $phoneNumber[0] == '+' ? $phoneNumber : '+' . $phoneNumber;

        Twilio::message($phoneNumber, $message);
    }

    public function chargers()
    {
        return $this -> hasMany(Charger::class);
    }

    public function car_models()
    {
        return $this -> belongsToMany('App\CarModel', 'user_car_models','user_id','model_id') -> withPivot('user_id');
    }

    public function user_cards()
    {
        return $this -> hasMany('App\UserCard');
    }

    public function orders()
    {
        return $this -> hasMany('App\Order');
    }

    public function active_orders()
    {
        return $this -> hasMany('App\Order') -> where( 'charging_status', '!=' , OrderStatus :: FINISHED );
    }

    public function user_cars()
    {
        return $this -> hasMany('App\UserCarModel');
    }

    public function favorites()
    {
        return $this->belongsToMany(Charger::class, 'favorites', 'user_id', 'charger_id')->withTimeStamps();
    }

    public function role()
    {
        return $this -> belongsTo('App\Role');
    }

    public function user_chargers()
    {
        return $this -> hasMany('App\ChargerUser');
    }

    public function business_services()
    {
        return $this -> hasMany(BusinessService::class);
    }
}
