<?php

namespace App\Classes;

use App\Classes\Grid;
use App\Classes\VesselFactory;
use \Session;

final class GameEngine
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
    private function saveGrid(Grid $grid): void
    {
        Session::put(static::SESSION_VAR_GRID, $grid);
    }

    public function getGrid(): Grid
    {
        return Session::get(static::SESSION_VAR_GRID);
    }

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
    * Take shot.
    *
    * Fire on a given alphanumeric grid coordinate (e.g. a1, j10).
    *
    * @param string $alphaCoordinate The alphanumeric grid coordinate to fire upon.
    * @return string $damageReport
    *
    */
    public function takeShot(string $alphaCoordinate)
    {
        $grid = $this->getGrid();
        $gridCoordinate = $grid->translateAlphaGridCoordinate($alphaCoordinate);
        $damageReport = $grid->takeShot($gridCoordinate);
        return $damageReport;
    }

    /**
     * Count shots.
     *
     * Returns number shots fired on the grid.
     *
     * @return int Total number of shots fired.
     *
     */
    public function countShots(): int
    {
        return $this->getGrid()->countShots();
    }

    /**
     * Game over?
     *
     * Returns true if all vessels sunk.
     *
     * @return bool $gameOver Return true if all vessels sunk, false if not.
     */
    public function gameOver(): bool
    {
        $grid = $this->getGrid();
        $vessels = $grid->getVessels();

        foreach ($vessels as $vessel) {
            if ( ! $vessel->sunk()) {
                return false;
            }
        }

        return true;
    }

    /**
     *
     *
     */
    public function toggleShowVessels(): void
    {
        $grid = $this->getGrid();
        $grid->toggleShowVessels();

        var_dump($grid->getShowVessels());

        $this->saveGrid($grid);
    }
}
