<?php

namespace App\Classes;

use App\Classes\GridCoordinate;
use App\Classes\GridCoordinateCollection;
use App\Classes\GridCoordinateStatusFactory;
use App\Classes\GridCoordinateStatusHit;
use App\Exceptions\InvalidKeyException;

    /**
     * Base class for all vessels.
     *
     */
abstract class Vessel
{
    protected $size = 0;
    protected $gridCoordinates = array();
    protected $gridCoordinateCollection;

    public function __construct(int $size)
    {
        $this->size = $size;
        $this->gridCoordinateCollection = new GridCoordinateCollection();
    }

    /**
    * @todo - add doc notation!
    */
    public function setGridCoordinateCollection(GridCoordinateCollection $gridCoordinateCollection): void
    {
        $this->gridCoordinateCollection = $gridCoordinateCollection;
    }

    /**
    * @todo - add doc notation!
    */
    public function getGridCoordinateCollection(): GridCoordinateCollection
    {
        return $this->gridCoordinateCollection;
    }

    /**
     * Add grid coordinate
     *
     * Add a grid coordinate to the internal collection of grid coordinates occupied by this vessel.
     *
     * @param GridCoordinate $gridCoordinate
     * @return void
     */
    public function addGridCoordinate(GridCoordinate $gridCoordinate): void
    {
        $this->getGridCoordinateCollection()->addItem($gridCoordinate);
    }

    /**
     * Get size
     *
     * Get the number of grid squares consumed by this vessel.
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Add damage
     *
     * Sets a HIT status on the specified grid coordinate, if they are valid.
     *
     * @param GridCoordinate $gridCoordinate
     * @return bool
     */
    public function addDamage(GridCoordinate $gridCoordinate): bool
    {
        $key = $gridCoordinate->getColonSeparatedKey();

        try {
            $gridCoordinate = $this->getGridCoordinateCollection()->getItem($key);
        } catch (InvalidKeyException $e) {
            return false;
        }

        $gridCoordinate->setStatus(GridCoordinateStatusFactory::make(GridCoordinateStatusFactory::HIT));
        return true;
    }

    /**
     * @todo - add doc notation!
     */
    public function getHitCoordinates(): array
    {
        // Iterate over $this->gridCoordinateCollection and return 'hit' ones.
        return $this->damage ?? array();
    }

    /**
     * Sunk
     *
     * Detect if this ship has sunk due to catastrophic damage.
     *
     * @todo - Should have used a proper iterator object here (ran out of time)!
     * @todo - use of instanceof GridCoordinateStatusHit is poor - needs refactoring to make it cleaner.
     *
     * @return bool
     */
    public function sunk(): bool
    {
        $coordinateCollection = $this->getGridCoordinateCollection();
        $keys = $coordinateCollection->getKeys();

        $hitCount = 0;

        foreach ($keys as $key) {
            if ($coordinateCollection->getItem($key)->getStatus() instanceof GridCoordinateStatusHit) {
                $hitCount++;
            }
        }

        return ($hitCount === $coordinateCollection->length());
    }
}
