<?php

namespace App\Classes;

    /**
     * Base class for all vessels.
     *
     */
abstract class Vessel
{
    protected $size = 0;
    protected $gridCoordinates = array();
    protected $damage = array();

    public function __construct(int $size)
    {
        $this->size = $size;
    }

    /**
     * Set the coordinates of all the grid spaces consumed by this (or rather, the child) vessel instance.
     *
     */
    public function setGridCoordinates(array $gridCoordinates): void
    {
        $this->gridCoordinates = $gridCoordinates;
    }

    /**
     * Get the coordinates of all the grid spaces consumed by this (or rather, the child) vessel instance.
     *
     */
    public function getGridCoordinates(): array
    {
        return $this->gridCoordinates;
    }

    /**
     * Get the number of grid squares consumed by this vessel.
     *
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     *
     *
     */
    public function addDamage(string $coord): bool
    {
        if (in_array($coord, $this->gridCoordinates)) {
            $this->damage[] = $coord;
            return true;
        }

        return false;
    }

    /**
     *
     *
     */
    public function getDamage(): array
    {
        return $this->damage ?? array();
    }

    /**
     *
     *
     */
    public function sunk(): bool
    {
        return (count($this->damage) === count($this->gridCoordinates));
    }
}
