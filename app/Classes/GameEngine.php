<?php

namespace App\Classes;

use App\Classes\Grid;
use App\Classes\VesselFactory;
use \Session;

class GameEngine
{
    const SESSION_VAR_GRID = 'grid';

    /**
     * Start or restart the game.
     *
     * Drops all session data and regenerates the grid.
     *
     * @returns void
     */
    public function reset(): void
    {
        $grid = new Grid();

        $grid->placeVessel(VesselFactory::make('Battleship'));
        $grid->placeVessel(VesselFactory::make('Battleship'));
        $grid->placeVessel(VesselFactory::make('Destroyer'));
        
        $this->saveGrid($grid);
    }

    /**
     * .
     *
     * .
     *
     * @returns 
     */
    public function loadVessels()
    {
        return Session::get(static::SESSION_VAR_GRID)->getVessels();
    }

    /**
     * .
     *
     * .
     *
     * @returns 
     */
    public function saveGrid(Grid $grid): void
    {
        Session::put(static::SESSION_VAR_GRID, $grid);
    }

    public function getGrid(): Grid
    {
        return Session::get(static::SESSION_VAR_GRID);
    }

    /**
     * .
     *
     * .
     *
     * @returns 
     */
    // public static function getGridData(): 
    // {
        
    // }

    /**
     * Check to see if a game has already been started.
     *
     * Assumes game in progress if session data exists.
     *
     * @return bool
     */
    public function inProgress(): bool
    {
        return (bool) Session::get(static::SESSION_VAR_GRID);
    }

    /**
    *
    *
    */
    public function takeShot(string $alphaCoordinate)
    {
        $grid = $this->getGrid();
        $gridCoord = $grid->translateAlphaGridCoordinate($alphaCoordinate);
        $damageReport = $grid->takeShot($gridCoord);
        return $damageReport;
    }
}
