<?php

namespace App\Classes;

use App\Classes\Vessel;

class VesselCollection extends Collection
{
    const ALLOWED_CLASS = 'App\Classes\Vessel';

    public function __construct()
    {
        parent::__construct(self::ALLOWED_CLASS);
    }
}
