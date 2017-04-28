<?php

namespace App\Classes;

class VesselBattleship extends Vessel
{
    const SIZE = 5;

    public function __construct()
    {
        parent::__construct(self::SIZE);
    }
}
