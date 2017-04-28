<?php

namespace App\Classes;

class VesselDestroyer extends Vessel
{
    const SIZE = 4;

    public function __construct()
    {
        parent::__construct(self::SIZE);
    }
}
