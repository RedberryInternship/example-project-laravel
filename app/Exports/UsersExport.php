<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

use App\User;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return self :: getData();
    }

    /**
     * Get all the users info.
     * 
     * @return array
     */
    public static function getData()
    {
        return User :: all() -> map( function( $user ) {
            return [
                'ID'                => $user -> id,
                'სახელი'            => $user -> first_name,
                'გვარი'             => $user -> last_name,
                'ტელეფონის ნომერი'  => $user -> phone_number,
            ];
        });
    }
}
