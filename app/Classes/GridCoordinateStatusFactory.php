<?php

namespace App\Classes;

use \Exception;

class GridCoordinateStatusFactory
{
    const NO_SHOT = 'NoShot';
    const MISS = 'Miss';
    const HIT = 'Hit';

    public static function make(string $statusClass)
    {
        $class = "App\Classes\\GridCoordinateStatus" . $statusClass;

        if ( ! class_exists($class)) {
            throw new Exception("Class name '" . $class . "' not recognised.");
        }

        return new $class();
    }
}