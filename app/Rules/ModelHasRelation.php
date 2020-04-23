<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ReflectionClass;

class ModelHasRelation implements Rule
{

    /**
     * Model name.
     * 
     * @var string
     */
    private $model;


    /**
     * Model relation.
     * 
     * @var string
     */
    private $relation;

    /**
     * Create a new rule instance and save relation.
     *
     * @return void
     */
    public function __construct( $model, $relation )
    {
        $this -> relation = $relation;
        $this -> model    = $model;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $modelInstance = $this -> model :: with( $this -> relation ) -> find($value);
        $relation      = $this -> relation;
        
        if( $modelInstance -> $relation )
        {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        
        $reflection = new ReflectionClass( $this -> model );

        return $reflection -> getShortName() . ' doesn\'t have ' . $this -> relation . ' relation.';
    }
}
