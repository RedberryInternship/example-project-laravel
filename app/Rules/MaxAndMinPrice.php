<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxAndMinPrice implements Rule
{
    /**
     * Minimal available price.
     * 
     * @var double|null
     */
    private $minPrice; 
    
    /**
     * Maximal available price.
     * 
     * @var double|null
     */
    private $maxPrice; 

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($minPrice, $maxPrice)
    {
      $this->minPrice = $minPrice;
      $this->maxPrice = $maxPrice;
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
        if($this->minPrice === null || $this->maxPrice === null)
        {
          return true;
        }
        $submittedPrice = (double) request()->get('price');
        
        return $submittedPrice >= $this->minPrice && $submittedPrice <= $this->maxPrice;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __(
            'business.chargers.price-not-in-range', 
            [
              'min_price' => $this->minPrice, 
              'max_price' => $this->maxPrice,
            ]
        );
    }
}
