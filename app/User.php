<?php

namespace App;

use App\Facades\SMS;
use App\Enums\OrderStatus;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'deactivated_at'    => 'datetime',
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

        return SMS::sendSms([
            'message'     => $message,
            'phoneNumber' => self::modifyPhoneNumberFormat($phoneNumber)
        ]);
    }

    /**
     * Get full name of the user.
     * 
     * @return string
     */
    public function fullName()
    {
        return $this -> first_name . ' ' . $this -> last_name;
    }

    public function company()
    {
        return $this -> belongsTo(Company::class);
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
        return $this -> hasMany(UserCard :: class) -> where( 'active', true );
    }

    public function orders()
    {
        return $this -> hasMany('App\Order');
    }

    public function active_orders()
    {
        return $this 
            -> hasMany('App\Order') 
            -> where(
                [
                    [ 'charging_status', '!=' , OrderStatus :: FINISHED       ],
                    [ 'charging_status', '!=' , OrderStatus :: UNPLUGGED      ],
                    [ 'charging_status', '!=' , OrderStatus :: CANCELED       ],
                    [ 'charging_status', '!=' , OrderStatus :: BANKRUPT       ],
                    [ 'charging_status', '!=' , OrderStatus :: PAYMENT_FAILED ],
                    [ 'charging_status', '!=' , OrderStatus :: NON_APP_MODE ],
                ]
            );
    }

    public function orders_history()
    {
        return $this
            -> hasMany( Order :: class )
            -> where( 'charging_status', OrderStatus :: FINISHED )
            -> whereNotNull( 'charger_name' )
            -> orderBy( 'id', 'desc' );
    }

    public function user_cars()
    {
        return $this -> hasMany('App\UserCarModel');
    }

    public function getFormatedUserCars()
    {
        $userCars = [];
        foreach ($this -> car_models as $userCarModel)
        {
            $userCars[] = [
                'user_id'  => $this -> id,
                'user_car' => [
                    'model_id'   => $userCarModel -> id,
                    'model_name' => $userCarModel -> name,
                    'mark_id'    => $userCarModel -> mark -> id,
                    'mark_name'  => $userCarModel -> mark -> name,
                ]
            ];
        }

        return $userCars;
    }

    public function favorites()
    {
        return $this -> belongsToMany(Charger::class, 'favorites', 'user_id', 'charger_id') -> withTimeStamps();
    }

    public function role()
    {
        return $this -> belongsTo('App\Role');
    }

    public function business_services()
    {
        return $this -> hasMany(BusinessService::class);
    }

    public function chargerGroups()
    {
        return $this -> hasMany(ChargerGroup::class) -> withPivot([
            'name',
            'charger_id'
        ]);
    }

    public function scopeAssignableChargerUsers($query)
    {
        return $query -> whereIn('role_id', [2, 3]);
    }

    public static function getAssignableChargerUsers()
    {
        return self::assignableChargerUsers()
            -> get()
            -> keyBy('id')
            -> map(function($user) {
                return $user -> first_name . ' ' . $user -> last_name;
            })
            -> toArray();
    }

    /**
     * Find User By Field Name.
     * 
     * @param $field
     * @param $value
     */
    public static function findBy($field, $value)
    {
        return self::where($field, $value) -> first();
    }

    public static function modifyPhoneNumberFormat($phoneNumber)
    {
        if (strlen($phoneNumber) == 9)
        {
            $phoneNumber = '+995' . $phoneNumber;
        }
        else if (strlen($phoneNumber) == '12' && $phoneNumber[0] != '+')
        {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Deactivate user by deleting every personal info,
     * that exists in db.
     * 
     * @return void
     */
    public function deactivate()
    {
        $this -> phone_number   = '---';
        $this -> first_name     = '---';
        $this -> last_name      = '---';
        $this -> password       = '---';
        $this -> email          = '---';
        $this -> active         = false;
        $this -> deactivated_at = now();
        $this -> save();

        UserCard :: where('user_id', $this -> id)
            -> update(
                [
                    'masked_pan'        => '---',
                    'transaction_id'    => '---',
                    'card_holder'       => '---',
                    'prrn'              => '---',
                ]
            );
    }
}
