<?php

namespace App\Classes;

use \Exception;
use App\Classes\Vessel;
use App\Classes\BattleshipVessel;
use App\Classes\DestroyerVessel;

/**
 * Simple factory class for spawning new vessels.
 */
class VesselFactory
{
    /**
     * Instantiate and return a new vessel object, based on the specified vessel type/class.
     *
     * @param string $vesselType
     * @throws 
     * @returns Vessel
     */
    public static function make(string $vesselType): Vessel
    {
        // try {
            $class = $vesselType . 'Vessel';
            // $vesselObject = new $class();
            $vesselObject = new BattleshipVessel();
        // } catch (\Exception $e) {
        //     //die();
        // }

        return $vesselObject;
    }
}