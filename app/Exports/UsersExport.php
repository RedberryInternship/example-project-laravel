<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use App\User;

class UsersExport extends StringValueBinder implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return self :: getData();
    }

    /**
     * Get all the users info.
     * 
     * @return array
     */
    public static function getData(): array
    {
        return User :: all()
            -> filter( function( $user ) {
                return strlen( $user -> phone_number ) > 12;
            })
            -> map( function( $user ) {
                return [
                    'phone_number' => $user -> phone_number,
                    'first_name'   => $user -> first_name,
                    'last_name'    => $user -> last_name,
                ];
            }) 
            -> toArray();
    }

    /**
     * Set headings for excel data.
     * 
     * @return array
     */
    public function headings(): array
    {
        return [ 'phone_number', 'first_name', 'last_name' ];
    }
}
