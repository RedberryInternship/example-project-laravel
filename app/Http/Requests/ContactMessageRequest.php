<?php

namespace App\Http\Requests;

use App\ContactMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class ContactMessageRequest extends FormRequest implements ValidatesWhenResolved
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required'
        ];
    }

    /**
     * Handle Failed Validation.
     * 
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $jsonResponse = response() -> json(['error' => $validator -> errors()], 422);

        throw new HttpResponseException($jsonResponse);
    }

    /**
     * Store Contact Form Message in DB.
     * 
     * @return void
     */
    public function store()
    {
        $user = auth('api') -> user();

        $this -> merge([
            'user_id' => $user ? $user -> id : null
        ]);

        ContactMessage::create($this -> all());
    }
}
