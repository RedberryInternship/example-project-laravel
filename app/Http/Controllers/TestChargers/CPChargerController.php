<?php

namespace App\Http\Controllers\TestChargers;

use App\Library\Chargers\Chargers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CPChargerController extends Controller
{
    public function getIndex(Chargers $chargers, $chargerID)
    {
        $chargers -> cp($chargerID);
    }
}
