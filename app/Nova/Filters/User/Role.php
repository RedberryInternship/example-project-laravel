<?php

namespace App\Nova\Filters\User;

use App\Role as RoleModel;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;


class Role extends Filter
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
      return $query -> where( 'role_id', $value );
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
      $roles = RoleModel :: all() -> map(function( $role ) {
          return [
            'id'   => $role -> id,
            'name' => $role -> name,
          ];
        })
        -> toArray();
      
      return array_combine( array_column( $roles, 'name' ), array_column( $roles, 'id' ) );
    }
}