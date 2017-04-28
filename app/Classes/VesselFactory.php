<?php

namespace App\Classes;

use \Exception;
use App\Classes\Vessel;

class VesselFactory
{
    const DESTROYER = 'Destroyer';
    const BATTLESHIP = 'Battleship';

    /**
     * Instantiate and return a new vessel object, based on the specified vessel type/class.
     *
     * @param string $vesselType
     * @return Vessel
     */
    public static function make(string $vesselType): Vessel
    {
        $class = 'App\Classes\\Vessel' . $vesselType;

        if ( ! class_exists($class)) {
            throw new Exception("Specified class '{$class}' does not exist.");
        }

        return new $class();
    }
}
