<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const TYPE_PERVICHKA = 1;
    const TYPE_VTORICHKA = 2;
    const TYPE_GRANULA = 0;

    const TO_PRODUCTION = -1;
    const TO_GRANULATOR = -2;
    const TO_FACTORY = -3;
    const TO_BACK = -4;

    const IMPORT_STATUS_WHITE = 0;
    const IMPORT_STATUS_GREEN = 1;
    const IMPORT_STATUS_YELLOW = 2;
    const IMPORT_STATUS_RED = 3;

    const EXPORT_STATUS_REQUEST = 0;
    const EXPORT_STATUS_CONFIRMED = 1;

    const CODE_VALIDATION = 1;
    const CODE_EXPORT_NOT_ENOUGH = 20;
    const CODE_DB_TRANSACTION = 30;
    const CODE_IMPORT_UPDATE_NOT_POSSIBLE = 40;
}
