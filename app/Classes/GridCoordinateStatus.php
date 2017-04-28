<?php

namespace App\Classes;

abstract class GridCoordinateStatus
{
    protected $status;

    /**
     * @todo - add doc notation!
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * @todo - add doc notation!
     */
    public function getStatus(): string{
        return $this->status;
    }
}