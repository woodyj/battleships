<?php

namespace App\Classes;

use App\Classes\GridCoordinateStatus;

final class GridCoordinate
{
    const STATUS_HIT = 'X';
    const STATUS_MISS = '-';
    const STATUS_NO_SHOT = '.';

    private $x;
    private $y;
    private $status;

    /**
     * @todo - add doc notations!
     *
     */
    public function __construct(int $x, int $y, GridCoordinateStatus $gridCoordinateStatus)
    {
        $this->setX($x);
        $this->setY($y);
        $this->setStatus($gridCoordinateStatus);
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function getStatus(): GridCoordinateStatus
    {
        return $this->status;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function setStatus(GridCoordinateStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public function getColonSeparatedKey(): string
    {
        return $this->x . ':' . $this->y;
    }

    /**
     * @todo - add doc notations!
     *
     */
    public static function getColonSeparatedKeyStatic($x, $y): string
    {
        return $x . ':' . $y;
    }

    /**
     * @todo - use a Validator class to check the format of $alphaCoordinate
     * @todo - add doc notation!
     */
    public static function translateAlphaGridCoordinate(string $alphaCoordinate): string
    {
        $alphaCoordinate = strtolower($alphaCoordinate);

        preg_match('/^([[:alpha:]])([[:digit:]]{1,2})$/', $alphaCoordinate, $matches);

        $x = strpos(implode(range('a', 'z')), $matches[1]) + 1;
        $y = (int)$matches[2];

        return self::getColonSeparatedKeyStatic($x, $y);
    }
}
