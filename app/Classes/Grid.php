<?php

namespace App\Classes;

use App\Classes\Vessel;
use \Exception;
use \stdClass;

final class Grid
{
    const ROWS = 10;
    const COLS = 10;
    const ORIENTATION_VERTICAL = 0;
    const ORIENTATION_HORIZONTAL = 1;
    const MAX_PLACEMENT_TRIES = 1000;
    const COORD_STATUS_HIT = 'X';
    const COORD_STATUS_MISS = '-';
    const COORD_STATUS_NO_SHOT = '.';

    private $coordinateStatuses = array();
    private $shots = array();
    private $vessels = array();
    private $showVessels = false;

    public function __construct()
    {
        $this->initialiseGridStatuses();
    }

    private function initialiseGridStatuses(): void
    {
        for ($x=1; $x<=static::COLS; $x++) {
            for ($y=1; $y<=static::ROWS; $y++) {
                $coordinateStatus = new stdClass;
                $coordinateStatus->status = static::COORD_STATUS_NO_SHOT;
                $coordinateStatus->x = $x;
                $coordinateStatus->y = $y;
                $coordinateStatus->hasVessel = false;

                $this->coordinateStatuses[$this->generateCoordinateValuePair($x, $y)] = $coordinateStatus;
            }
        }
    }

    /**
     * Toggle showVessels
     *
     * Show/hide vessel location in grid data
     *
     */
    public function toggleShowVessels(): void
    {
        $this->showVessels = !$this->showVessels;
    }

    public function getShowVessels(): bool
    {
        return $this->showVessels;
    }

    /**
     *
     *
     */
    public function getCoordinateStatuses(): array
    {
        $coordinateStatuses = $this->coordinateStatuses;

        // Add on the damage from each ship - best to keep it this way so there is a single source of truth for battle damage.
        foreach ($this->getVessels() as $vessel) {
            foreach ($vessel->getGridCoordinates() as $coordinate) {
                $coordinateStatuses[$coordinate]->hasVessel = true;
            }

            foreach ($vessel->getDamage() as $coordinate) {
                $coordinateStatuses[$coordinate]->status = static::COORD_STATUS_HIT;
            }
        }

        return $coordinateStatuses;
    }

    public function setGridCoordinateStatus(string $coordinate, string $status): void
    {
        $this->coordinateStatuses[$coordinate]->status = $status;
    }

    /**
     * Get Vessels.
     *
     * Get the vessels stored by this grid object.
     *
     * @return array[Vessel] List of vessels stored by this grid object.
     */
    public function getVessels(): array
    {
        return $this->vessels;
    }

    public function saveVessel(Vessel $vessel): void
    {
        $this->vessels[] = $vessel;
    }

    /**
     * Attempt to place the vessel on the grid.
     *
     * Randomly generate a grid starting position, based on vessel size and orientation.
     *
     */
    public function placeVessel(Vessel $vessel): void
    {
        $tries = 0;

        do {
            if ($tries >= static::MAX_PLACEMENT_TRIES) {
                throw new Exception('Max number of vessel placement tries exceeded!');
            }

            $orientation = rand(static::ORIENTATION_HORIZONTAL, static::ORIENTATION_VERTICAL);

            switch ($orientation) {
                case static::ORIENTATION_HORIZONTAL:
                    $startingPositionX = rand(
                        1,
                        $this->calculateMaxStartingPosition($vessel, $orientation)
                    );
                    $startingPositionY = rand(1, static::COLS);

                    break;
                case static::ORIENTATION_VERTICAL:
                    $startingPositionX = rand(1, static::ROWS);
                    $startingPositionY = rand(
                        1,
                        $this->calculateMaxStartingPosition($vessel, $orientation)
                    );
            }

            $newGridCoordinates = $this->generateVesselGridCoordinates(
                $startingPositionX,
                $startingPositionY,
                $orientation,
                $vessel
            );

            $vessel->setGridCoordinates($newGridCoordinates);
            $positionValid = $this->checkVesselPosition($vessel);
            $tries++;

        } while ( ! $positionValid);

        $this->saveVessel($vessel);
    }

    /**
     *
     *
     *
     */
    private function calculateMaxStartingPosition(Vessel $vessel, int $orientation): int
    {
        switch ($orientation) {
            case static::ORIENTATION_VERTICAL:
                $maxStartingPosition = static::ROWS - $vessel->getSize();
                break;
            case static::ORIENTATION_HORIZONTAL:
                $maxStartingPosition = static::COLS - $vessel->getSize();
        }

        return $maxStartingPosition;
    }

    /**
     * Check Vessel position.
     *
     * Determine if new vessel position overlaps a preoccupied one.
     *
     * @param Vessel $vessel
     * @return bool
     */
    public function checkVesselPosition(Vessel $vessel): bool
    {
        foreach ($this->vessels as $existingVessel) {
            foreach ($vessel->getGridCoordinates() as $vesselCoordinate) {
                foreach ($existingVessel->getGridCoordinates() as $existingVesselCoordinate) {
                    if ($vesselCoordinate === $existingVesselCoordinate) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Generate random coordinates for placing of new vessels.
     *
     * @param int $startingPositionX The horizontal starting position of the vessel.
     * @param int $startingPositionY The vertical starting position of the vessel.
     * @param int $orientation The orientation (static::ORIENTATION_VERTICAL or static::ORIENTATION_HORIZONTAL) of the vessel.
     *
     * @return array $coordinates An array of 'X:Y' e.g. ('2:8') coordinate strings, one for each grid square.
     */
    private function generateVesselGridCoordinates(
        int $startingPositionX,
        int $startingPositionY,
        int $orientation,
        Vessel $vessel
    ): array
    {
        $startingCoordinate = $this->generateCoordinateValuePair($startingPositionX, $startingPositionY);
        $coordinates = array($startingCoordinate);

        for ($i=1; $i<$vessel->getSize(); $i++) {
            switch ($orientation) {
                case static::ORIENTATION_HORIZONTAL:
                    $x = $startingPositionX+$i;
                    $y = $startingPositionY;
                break;
                case static::ORIENTATION_VERTICAL:
                    $x = $startingPositionX;
                    $y = $startingPositionY+$i;
            }

            $coordinates[] = $this->generateCoordinateValuePair($x, $y);
        }

        return $coordinates;
    }

    /**
     * Stringify an X,Y coordinate into a colon separated value pair.
     *
     * @param int $x The horizontal coordinate.
     * @param int $y The vertical coordinate.
     * @return string The generated colon separated value pair.
     */
    private function generateCoordinateValuePair(int $x, int $y): string
    {
        return $x . ':' . $y;
    }

    /**
     * Translate alpha grid coordinate.
     *
     * Turn an alphanumeric grid coordinate (e.g. B8) into a numeric X:Y value pair (e.g. 2:8)
     *
     * @param string $alphaCoordinate The alphanumeric grid coordinate to be translated.
     * @return string $coordPair The generated, colon-separated value pair.
     */
    public function translateAlphaGridCoordinate(string $alphaCoordinate): string
    {
        $alphaCoordinate = strtolower($alphaCoordinate);

        preg_match('/^([[:alpha:]])([[:digit:]]{1,2})$/', $alphaCoordinate, $matches);

        $x = strpos(implode(range('a', 'z')), $matches[1]) + 1;
        $y = (int)$matches[2];

        $coordinate = $this->generateCoordinateValuePair($x, $y);

        return $coordinate;
    }

    /**
     * Take a shot.
     *
     * Registers a shot either missed or hit, required the grid coordinate is still free.
     *
     * @TODO - refactor this entire method! Needs to return something safer than an arbitrary string!
     */
    public function takeShot(string $coordinate)
    {
        // ignore this shot if this coordinate already fired on
        if ($this->getCoordinateStatuses()[$coordinate]->status !== static::COORD_STATUS_NO_SHOT) {
            return 'You already shot this one.';
            // @TODO - throw a custom exception here instead!
        }

        // check if this shot hits a vessel
        foreach ($this->getVessels() as $vessel) {
            if (in_array($coordinate, $vessel->getGridCoordinates())) {
                $vessel->addDamage($coordinate);

                if ($vessel->sunk()) {
                    return 'You sank a ship!';
                }

                return 'You hit a ship!';
            }
        }

        $this->setGridCoordinateStatus($coordinate, static::COORD_STATUS_MISS);

        return 'Miss!';
    }

    /**
     * Count shots.
     *
     * Return number of shots fired on this grid.
     *
     */
    public function countShots(): int
    {
        $i=0;

        foreach ($this->getCoordinateStatuses() as $key => $coordinateStatus) {
            if ($coordinateStatus->status !== static::COORD_STATUS_NO_SHOT) {
                $i++;
            }
        }

        return $i;
    }
}