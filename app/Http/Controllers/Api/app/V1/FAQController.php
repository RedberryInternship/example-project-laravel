<?php

namespace App\Http\Controllers\Api\app\V1;

use App\FAQ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FAQController extends Controller
{
    /**
     * Get FAQ.
     */
    public function __invoke()
    {
        $faq = FAQ::get();

        return response() -> json([
            'faq' => $faq
        ]);
    }
}
