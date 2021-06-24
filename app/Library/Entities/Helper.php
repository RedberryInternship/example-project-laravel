<?php

namespace App\Library\Entities;

use Illuminate\Support\Facades\File;
use Carbon\CarbonPeriod;
use App\Config;

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
        return app()->getLocale() === 'ka' ? [
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
        ] : [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'Jule',
            'August',
            'September',
            'October',
            'November',
            'December',
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

    /**
     * Day time range.
     *
     * @return array
     */
    public static function dayTimesRange(): array
    {
        $ranges = [];

        for($i=0; $i<=24; $i++)
        {
            $hrs = $i < 10 ? '0'.$i : strval($i);
            $ranges []= $hrs . ':00';
        }

        return $ranges;
    }

    /**
     * Create url with query parameters.
     *
     * @return string
     */
    public static function url( $uri, $params ): string
    {
        $uri .= '?';

        foreach( $params as $key => $value )
        {
            $uri .= '&' . $key . '=' .$value;
        }

        return $uri;
    }

    /**
     * Get penalty price per minute.
     *
     * @return float
     */
    public static function getPenaltyPricePerMinute()
    {
        $config                 = Config :: first();
        $penaltyPricePerMinute  = $config -> penalty_price_per_minute;

        return $penaltyPricePerMinute;
    }

    /**
     * Get initial charging price for
     * charging process.
     *
     * @return float
     */
    public static function getInitialChargingPrice()
    {
        return Config :: initialChargePrice();
    }

    /**
     * Get next charging prices for
     * charging process.
     *
     * @return float
     */
    public static function getNextChargingPrice()
    {
        return Config :: nextChargePrice();
    }

    /**
     * Convert minutes into hh-mm.
     *
     * @param int $minutes
     * @return string
     */
    public static function convertMinutesToHHMM($minutes): string
    {
        $hours = intval($minutes / 60);
        $minutes = $minutes % 60;


        if($hours < 10)
        {
            $hours = '0' . $hours;
        }

        if($minutes < 10)
        {
            $minutes = '0' . $minutes;
        }

        return $hours . ':' . $minutes;
    }

    /**
     * Remove tmp excel files.
     *
     * @return void
     */
    public static function removeTmpExcelFiles(): void
    {
        $files = File::allFiles(base_path('storage'));
        $filenames = array_map(function($file) { return $file->getFilename(); }, $files);
        $pattern = "/^laravel-excel-.*\.xlsx$/";

        foreach($filenames as $filename)
        {
           $matched = preg_match($pattern, $filename);
           if($matched)
           {
               $file = base_path('storage/'. $filename);
               unlink($file);
           }
        }
    }

    /**
     * Get nova url.
     *
     * @return string
     */
    public static function novaURL()
    {
       return config('app')['url'] . '/nova';
    }
}
