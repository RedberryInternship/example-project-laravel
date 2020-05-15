<?php

namespace App\Http\Controllers\Api\app\V1;

use App\FAQ;
use App\Http\Controllers\Controller;

class FAQController extends Controller
{
    /**
     * Get FAQ.
     */
    public function __invoke()
    {
        $faq = FAQ::all();

        return response() -> json([
            'faq' => $faq
        ]);
    }
}
