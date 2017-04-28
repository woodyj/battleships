<?php

namespace App\Classes;

use App\Classes\GridCoordinate;

class GridCoordinateCollection extends Collection
{
    const ALLOWED_CLASS = 'App\Classes\GridCoordinate';

    public function __construct()
    {
        parent::__construct(self::ALLOWED_CLASS);
    }

    /**
     * Force the use of X:Y key pair for GridCoordinateCollection
     *
     * @todo - add missing doc notation!
     */
    public function addItem($item, $key = null):void
    {
        parent::addItem($item, $item->getColonSeparatedKey());
    }
}
