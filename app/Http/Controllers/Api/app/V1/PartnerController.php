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

        foreach ($partners as &$partner)
        {
            $partner -> image = asset('/storage/' . $partner -> image);
        }

        return response() -> json([
            'partners' => $partners
        ]);
    }
}
