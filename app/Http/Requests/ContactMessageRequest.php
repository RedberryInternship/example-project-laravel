<?php

namespace App\Http\Requests;

use App\Contact;
use App\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

class ContactMessageRequest extends FormRequest implements ValidatesWhenResolved
{
    /**
     * Admin Mail Addresses.
     * 
     * @return array
     */
    protected function mailAddresses(): array
    {
        return [
            Contact :: first() -> email,
        ];
    }

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

    /**
     * Send Mail to Admins.
     */
    public function sendMail()
    {
        $user     = auth('api') -> user();

        $to       = $this -> mailAddresses();
        $from     = $user && $user -> email && filter_var($user -> email, FILTER_VALIDATE_EMAIL) ? $user -> email : 'unknown@example.com';
        $fromName = $user ? $user -> first_name . ' ' . $user -> last_name : 'unknown sender';

        $content  =
            "Contact Mail from: " . $fromName . "." . "<br>" .
            "Text: " . $this -> get('message');

        try {
            Mail::send([], [], function ($message) use ($to, $from, $content) {
                $message
                    -> to($to)
                    -> from($from, 'E-space App')
                    -> subject('Contact Form')
                    -> setBody($content, 'text/html; charset=utf-8');
            });
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
