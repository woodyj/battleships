<?php

namespace App\Classes;

use App\Classes\Grid;
use App\Classes\GridCoordinate;
use App\Classes\VesselFactory;
use App\Classes\GridCoordinateBuilder;
use \Session;
use \Exception;

final class GameFacade
{
    const SESSION_VAR_GRID = 'grid';

    /**
     * Start or restart the game.
     *
     * Drops all session data and regenerates the grid.
     *
     * @return void
     */
    public static function reset(): void
    {
        $grid = new Grid();

        $grid->placeVessel(VesselFactory::make('Battleship'));
        $grid->placeVessel(VesselFactory::make('Battleship'));
        $grid->placeVessel(VesselFactory::make('Destroyer'));
        
        self::saveGrid($grid);
    }

    /**
     * Save Grid
     *
     * Create/update the grid object stored in the current session.
     *
     * @return void
     */
    private static function saveGrid(Grid $grid): void
    {
        Session::put(self::SESSION_VAR_GRID, $grid);
    }

    /**
     * Get Grid
     *
     * Fetch and return the grid object stored in the current session.
     *
     * @return void
     */
    public static function getGrid(): Grid
    {
        return Session::get(self::SESSION_VAR_GRID);
    }

    /**
     * Check to see if a game has already been started.
     *
     * Assumes game in progress if session data exists.
     *
     * @return bool
     */
    public static function inProgress(): bool
    {
        return (bool) Session::get(self::SESSION_VAR_GRID);
    }

    /**
    * Take shot.
    *
    * Fire on a given X,Y coordinate.
    *
    * @param int $x
    * @param int $y
    * @return string $damageReport
    *
    */
    public static function takeShot(int $x, int $y)
    {
        $grid = self::getGrid();
        $gridCoordinateBuilder = new GridCoordinateBuilder($x, $y);
        $gridCoordinate = $gridCoordinateBuilder->build();

        try {
            $damageReport = $grid->takeShot($gridCoordinate);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return '';
    }

    /**
     * Count shots.
     *
     * Returns number shots fired on the grid.
     *
     * @return int Total number of shots fired.
     *
     */
    public static function countShots(): int
    {
        return self::getGrid()->getShotsFired();
    }

    /**
     * Over
     *
     * Check if it's 'game over' yet.
     *
     * @return bool Return true if all vessels sunk, false if not.
     */
    public static function over(): bool
    {
        $grid = self::getGrid();
        $vesselCollection = $grid->getVesselCollection();

        /**
         * @todo - need to use an Iterator here.
         */
        foreach ($vesselCollection->getKeys() as $key) {
            $vessel = $vesselCollection->getItem($key);

            if ( ! $vessel->sunk()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Toggle show Vessels
     *
     * Turn vessel visbility on/off in the grid object.
     *
     * @return void
     */
    public static function toggleShowVessels(): void
    {
        $grid = self::getGrid();
        $grid->toggleShowVessels();
        self::saveGrid($grid);
    }
}
