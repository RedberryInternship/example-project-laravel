<?php

namespace App\Library\Entities;

use Carbon\CarbonPeriod;

class Helper
{
    /**
     * Determine if application is in development mode.
     * 
     * @return bool
     */
    public static function isDev(): bool
    {
        return config( 'app.env' ) !== 'production';
    }

    /**
     * Get all available languages.
     * 
     * @return array
     */
    public static function allLang(): array
	{
		return ['ka', 'en', 'ru'];
    }
  
    /**
     * Old month list helper.
     * 
     * @return array
     */
    public static function getMonthList($start, $end)
    {
        $months = [];
        foreach (CarbonPeriod::create($start, '1 month', $end) as $month)
        {
            $months[] = $month -> format('Y-m');
        }

        return $months;
    }

    /**
     * Get monthly fresh data.
     * 
     * @return array
     */
    public static function getFreshMonthlyData(): array 
    {
        return [
            0 => 0,  # January
            1 => 0,  # February
            2 => 0,  # March
            3 => 0,  # April
            4 => 0,  # May
            5 => 0,  # June
            6 => 0,  # July
            7 => 0,  # August
            8 => 0,  # September
            9 => 0,  # October
            10 => 0, # November
            11 => 0, # December
        ];
    }

    /**
     * Get month names.
     * 
     * @return array
     */
    public static function getMonthNames(): array
    {
        return [
            'იანვარი',
            'თებერვალი',
            'მარტი',
            'აპრილი',
            'მაისი',
            'ივნისი',
            'ივლისი',
            'აგვისტო',
            'სექტემბერი',
            'ოქტომბერი',
            'ნოემბერი',
            'დეკემბერი',
        ];
    }

    /**
     * Convert watts to kilowatts.
     * 
     * @param array $watts
     * @return array
     */
    public static function convertWattsToKilowatts(array $watts): array
    {
        return array_map(function( $val ) { return $val /= 1000; }, $watts);
    }

    /**
     * Cast lists into desirable type.
     * 
     * @return mixed
     */
    public static function castListInto(&$list, $column, $type )
    {
        $caster = null;
        switch($type)
        {
            case 'int': $caster = function( $val ){ return intval($val); }; break;
            case 'float': $caster = function( $val ){ return floatval($val); }; break;
            default: throw new \Exception('Non acceptable type provided...');
        }

        array_walk($list, function( $el ) use($column, $caster) {
            if(isset($el -> $column))
            {
                $el -> $column = $caster($el -> $column);
            }
        });
    }
}