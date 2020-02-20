<?php

namespace App\Http\Controllers\TestChargers;

use App\Library\Chargers\Chargers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AllChargersController extends Controller
{
    public function getIndex(Chargers $chargers, $chargerID = null)
    {
        $chargers -> get($chargerID);
    }
}
