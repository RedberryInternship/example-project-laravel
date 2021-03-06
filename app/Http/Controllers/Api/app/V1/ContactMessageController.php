<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;

class ContactMessageController extends Controller
{
    /**
     * Store Contact Form Message in DB.
     * 
     * @param ContactMessageRequest $request
     * 
     * @return JSON
     */
    public function __invoke(ContactMessageRequest $request)
    {
        $request -> store();

        $sent = $request -> sendMail();

        return response() -> json([], $sent ? 200 : 400);
    }
}
