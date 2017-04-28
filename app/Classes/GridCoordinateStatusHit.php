<?php

namespace App\Classes;

use App\Classes\GridCoordinateStatus;

class GridCoordinateStatusHit extends GridCoordinateStatus
{
    const STATUS = 'X';

    /**
     * @todo - add doc notation!
     */
    public function __construct()
    {
        parent::__construct(self::STATUS);
    }
}