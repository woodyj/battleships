<?php

namespace App\Classes;

use \Exception;
use App\Classes\Vessel;

/**
 * Simple factory class for spawning new vessels.
 */
class VesselFactory
{
    /**
     * Instantiate and return a new vessel object, based on the specified vessel type/class.
     *
     * @param string $vesselType
     * @returns Vessel
     */
    public static function make(string $vesselType): Vessel
    {
        $class = 'App\Classes\\' . $vesselType . 'Vessel';

        if ( ! class_exists($class)) {
            throw new Exception("Specified class '{$class}' does not exist.");
        }

        return new $class();
    }
}