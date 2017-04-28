<?php

namespace App\Classes;

use App\Classes\Grid;

class GridAsNumericArrayStrategy
{
    /**
     * @todo - refactor this method to use an Iterator - it's VERY inefficient at the moment!
     */
    public static function get(Grid $grid): array
    {
        $data = array();
        $gridCoordinates = $grid->getGridCoordinateCollection();

        foreach ($gridCoordinates->getKeys() as $key) {
            $gridCoordinate = $gridCoordinates->getItem($key);
            $data[$gridCoordinate->getX()][$gridCoordinate->getY()] = array(
                'status' => $gridCoordinate->getStatus()->getStatus(),
                'highlight' => false
            );
        }

        // unset($gridCoordinate, $gridCoordinates);
        $vessels = $grid->getVesselCollection();
        $vesselKeys = $vessels->getKeys();

        foreach ($vesselKeys as $vesselKey) {
            $vessel = $vessels->getItem($vesselKey);
            $gridCoordinates = $vessel->getGridCoordinateCollection();
            $gridCoordinateKeys = $gridCoordinates->getKeys();

            foreach ($gridCoordinateKeys as $gridCoordinateKey) {
                $gridCoordinate = $gridCoordinates->getItem($gridCoordinateKey);
                $dataElement = &$data[$gridCoordinate->getX()][$gridCoordinate->getY()];
                $dataElement['status'] = $gridCoordinate->getStatus()->getStatus();

                if ($grid->getShowVessels()) {
                    $dataElement['highlight'] = true;
                }
            }
        }

        return $data;
    }
}