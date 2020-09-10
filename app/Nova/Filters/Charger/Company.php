<?php

namespace App\Nova\Filters\Charger;

use App\Company as CompanyModel;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Company extends Filter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
      return $query -> where( 'company_id', $value );
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
      $companies = CompanyModel :: all() -> map(function( $company ) {
          return [
            'id'   => $company -> id,
            'name' => $company -> getTranslations('name')['en'],
          ];
        })
        -> toArray();
      
      return array_combine( array_column( $companies, 'name' ), array_column( $companies, 'id' ) );
    }
}
