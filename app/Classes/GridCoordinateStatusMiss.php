<?php

namespace App\Classes;

use App\Classes\GridCoordinateStatus;

class GridCoordinateStatusMiss extends GridCoordinateStatus
{
    const STATUS = '-';

    /**
     * @todo - add doc notation!
     */
    public function __construct()
    {
        parent::__construct(self::STATUS);
    }
}
