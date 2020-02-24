<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
    /**
     * Get Partners.
     */
    public function __invoke()
    {
        $partners = Partner::all();

        return response() -> json([
            'partners' => $partners
        ]);
    }
}
