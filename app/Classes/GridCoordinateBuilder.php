<?php

namespace App\Classes;

use App\Classes\GridCoordinate;
use App\Classes\GridCoordinateStatusFactory;

final class GridCoordinateBuilder
{
    private $x;
    private $y;
    private $coordinateStatus;
    private $configs = array();

    public function __construct($x, $y, CoordinateStatus $coordinateStatus = null)
    {
        if ($coordinateStatus instanceof CoordinateStatus) {
            $this->coordinateStatus = $coordinateStatus;
        } else {
            $this->coordinateStatus = GridCoordinateStatusFactory::make(GridCoordinateStatusFactory::NO_SHOT);
        }

        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @todo - add doc notation
     */
    public function build(): GridCoordinate
    {
        $gridCoordinate = new GridCoordinate($this->x, $this->y, $this->coordinateStatus);
        return $gridCoordinate;
    }
}
