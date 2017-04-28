<?php

namespace App\Classes;

use App\Classes\Vessel;
use App\Classes\GridCoordinateCollection;
use App\Classes\GridCoordinateBuilder;
use App\Classes\VesselCollection;
use App\Classes\GridCoordinateStatusFactory;
// use App\Classes\GridCoordinateStatusHit;
// use App\Classes\GridCoordinateStatusMiss;
// use App\Classes\GridCoordinateStatusNoShot;
use \Exception;

final class Grid
{
    const ROWS = 10;
    const COLS = 10;
    const ORIENTATION_VERTICAL = 0;
    const ORIENTATION_HORIZONTAL = 1;
    const MAX_PLACEMENT_TRIES = 1000;

    private $gridCoordinateCollection;
    private $shotsFired = 0;
    private $vesselCollection;
    private $showVessels = false;

    public function __construct()
    {
        $this->gridCoordinateCollection = new GridCoordinateCollection();
        $this->initialiseGridCoordinateCollection();
        $this->vesselCollection = new VesselCollection();
    }

    /**
     * @todo - add doc notation!
     */
    public function getGridCoordinateCollection(): GridCoordinateCollection
    {
        return $this->gridCoordinateCollection;
    }

    /**
     * Initialise grid coordinates
     *
     * Setup a list of grid coordinates (or cells) to track user shots.
     *
     * @return void
     */
    private function initialiseGridCoordinateCollection(): void
    {
        for ($x=1; $x<=self::COLS; $x++) {
            for ($y=1; $y<=self::ROWS; $y++) {
                $gridCoordinateBuilder = new GridCoordinateBuilder($x, $y);
                $gridCoordinate = $gridCoordinateBuilder->build();
                $this->getGridCoordinateCollection()->addItem($gridCoordinate, $gridCoordinate->getColonSeparatedKey());
            }
        }
    }

    /**
     * Toggle showVessels
     *
     * Show/hide vessel location in grid data.
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
     * Set grid coordinate status
     *
     * Update a grid coordinate status (e.g. once it is fired upon).
     *
     * @param GridCoordinate $gridCoordinate
     * @param GridCoordinateStatus $gridCoordinate
     * @return void
     */
    public function setGridCoordinateStatus(GridCoordinate $gridCoordinate, GridCoordinateStatus $gridCoordinateStatus): void
    {
        $this->getGridCoordinateCollection()
            ->getItem($gridCoordinate->getColonSeparatedKey())
            ->setStatus($gridCoordinateStatus);
    }

    /**
     * Get vessel collection
     *
     * Get the vessel collection stored by this grid object.
     *
     * @return VesselCollection
     */
    public function getVesselCollection(): VesselCollection
    {
        return $this->vesselCollection;
    }

    /**
     * @todo - add doc notation!
     */
    private function addVessel(Vessel $vessel): void
    {
        $this->vesselCollection->addItem($vessel);
    }

    /**
     * Place vessel
     *
     * Randomly generate a grid starting position, based on vessel size and orientation.
     *
     * @return void
     */
    public function placeVessel(Vessel $vessel): void
    {
        $tries = 0;

        do {
            if ($tries >= self::MAX_PLACEMENT_TRIES) {
                throw new Exception('Max number of vessel placement tries exceeded!');
            }

            $orientation = rand(self::ORIENTATION_HORIZONTAL, self::ORIENTATION_VERTICAL);

            switch ($orientation) {
                case self::ORIENTATION_HORIZONTAL:
                    $startingPositionX = rand(
                        1,
                        $this->calculateMaxStartingPosition($vessel, $orientation)
                    );
                    $startingPositionY = rand(1, self::COLS);
                    break;

                case self::ORIENTATION_VERTICAL:
                    $startingPositionX = rand(1, self::ROWS);
                    $startingPositionY = rand(
                        1,
                        $this->calculateMaxStartingPosition($vessel, $orientation)
                    );
            }

            /**
             * @todo - refactor all of below (it's quite simply bollocks)
             */
            $gridCoordinateCollection = $this->generateVesselGridCoordinateCollection(
                $startingPositionX,
                $startingPositionY,
                $orientation,
                $vessel
            );

            $vessel->setGridCoordinateCollection($gridCoordinateCollection);
            $positionValid = $this->newVesselPositionUnique($vessel);
            $tries++;

        } while ( ! $positionValid);

        $this->addVessel($vessel);
    }

    /**
     * Calculate maximum starting position
     *
     * Figure out the furthest distance from top left corner - for a given orientation - that a vessel can be placed.
     *
     * @return int $maxStartingPosition The maximum starting position (along X or Y axis) for the given vessel and orientation.
     */
    private function calculateMaxStartingPosition(Vessel $vessel, int $orientation): int
    {
        switch ($orientation) {
            case self::ORIENTATION_VERTICAL:
                $maxStartingPosition = self::ROWS - $vessel->getSize();
                break;
            case self::ORIENTATION_HORIZONTAL:
                $maxStartingPosition = self::COLS - $vessel->getSize();
        }

        return $maxStartingPosition;
    }

    /**
     * New vessel position unique
     *
     * Determine if a new vessel's position overlaps a preoccupied one.
     *
     * @todo - urgghh - do this another way - brain stopped working at this point - could 
     * probably use a decorator to do some of this, and p*reserve the cleanliness of this class.
     *
     * @param Vessel $vessel
     * @return bool
     */
    public function newVesselPositionUnique(Vessel $newVessel): bool
    {
        $existingVesselCollection = $this->getVesselCollection();

        foreach ($existingVesselCollection->getKeys() as $existingVesselKey) {
            $existingVessel = $existingVesselCollection->getItem($existingVesselKey);

            if (array_intersect(
                $newVessel->getGridCoordinateCollection()->getKeys(), 
                $existingVessel->getGridCoordinateCollection()->getKeys())
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Generate vessel grid coordinates
     *
     * Generate random coordinates for placing of new vessels.
     *
     * @param int $startingPositionX The horizontal starting position of the vessel.
     * @param int $startingPositionY The vertical starting position of the vessel.
     * @param int $orientation The orientation (self::ORIENTATION_VERTICAL or self::ORIENTATION_HORIZONTAL) of the vessel.
     *
     * @return array $coordinates An array of 'X:Y' e.g. ('2:8') coordinate strings, one for each grid square.
     */
    private function generateVesselGridCoordinateCollection(
        int $startingPositionX,
        int $startingPositionY,
        int $orientation,
        Vessel $vessel
    ): GridCoordinateCollection
    {
        $coordinateCollection = new GridCoordinateCollection();
        $gridCoordinateBuilder = new GridCoordinateBuilder($startingPositionX, $startingPositionY);
        $gridCoordinate = $gridCoordinateBuilder->build();
        $coordinateCollection->addItem($gridCoordinate);

        for ($i=1; $i<$vessel->getSize(); $i++) {
            switch ($orientation) {
                case self::ORIENTATION_HORIZONTAL:
                    $x = $startingPositionX+$i;
                    $y = $startingPositionY;
                break;
                case self::ORIENTATION_VERTICAL:
                    $x = $startingPositionX;
                    $y = $startingPositionY+$i;
            }

            $gridCoordinateBuilder = new GridCoordinateBuilder($x, $y);
            $gridCoordinate = $gridCoordinateBuilder->build();
            $coordinateCollection->addItem($gridCoordinate);
        }

        return $coordinateCollection;
    }

    /**
     * Take shot
     *
     * Registers a shot either missed or hit, required the grid coordinate is still free.
     *
     * @todo - refactor this entire method! Needs to return something safer than an arbitrary string!
     * @todo - $coordinate argument needs to tightened to be stricter than accepting an arbitrary string.
     */
    public function takeShot(GridCoordinate $gridCoordinate)
    {
        // ignore this shot if this coordinate already fired on
        $coordinateStatus = $this->getGridCoordinateCollection()
            ->getItem($gridCoordinate->getColonSeparatedKey())
            ->getStatus();

        if ( ! $coordinateStatus instanceof GridCoordinateStatusNoShot) {
            throw new Exception('You already shot this one.');
        }

        // iterate through vessel collection and try to damage one:
        $vesselCollection = $this->getVesselCollection();

        foreach ($vesselCollection->getKeys() as $vesselKey) {
            $vessel = $vesselCollection->getItem($vesselKey);

            if ($vesselDamaged = $vessel->addDamage($gridCoordinate)) {
                if ($vessel->sunk()) {
                    $this->shotsFired++;
                    throw new Exception('You sank a ship!');
                }

                $this->shotsFired++;
                throw new Exception('You hit a ship!');
            }
        }

        $this->shotsFired++;

        $this->setGridCoordinateStatus(
            $gridCoordinate,
            GridCoordinateStatusFactory::make(GridCoordinateStatusFactory::MISS)
        );
    }

    /**
     * Get shots fired
     *
     * Return number of shots fired on this grid.
     *
     */
    public function getShotsFired(): int
    {
        return $this->shotsFired;
    }
}