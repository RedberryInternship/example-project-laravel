<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    /**
     * Return Contacts Data from DB.
     * 
     * @param ContactRequest $request
     */
    public function __invoke(ContactRequest $request)
    {
        return response() -> json($request -> data(), 200);
    }
}
