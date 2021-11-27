<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Handlers;

use Latuconsinafr\BinPackager\BinPackager3D\Item;
use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;

/**
 * Class to handle intersection between items.
 */
final class IntersectionHandler
{
    /**
     * The two rectangles intersection checker.
     * 
     * @param Item $firstItem The first item, in this case a rectangle.
     * @param Item $secondItem The second item, in this case a rectangle as well.
     * @param int $xAxis The x axis.
     * @param int $yAxis The y axis.
     * 
     * @return bool The flag indicates whether both items are intersected with each other or not, 
     * return true if both items are intersected with each other, otherwise false.
     */
    private static function isRectangleIntersected(Item $firstItem, Item $secondItem, string $xAxis, string $yAxis): bool
    {
        $firstDimension = $firstItem->getDimension();
        $secondDimension = $secondItem->getDimension();

        $firstCx = $firstItem->getPosition()[$xAxis] + $firstDimension[$xAxis] / 2;
        $firstCy = $firstItem->getPosition()[$yAxis] + $firstDimension[$yAxis] / 2;

        $secondCx = $secondItem->getPosition()[$xAxis] + $secondDimension[$xAxis] / 2;
        $secondCy = $secondItem->getPosition()[$yAxis] + $secondDimension[$yAxis] / 2;

        $ix = max($firstCx, $secondCx) - min($firstCx, $secondCx);
        $iy = max($firstCy, $secondCy) - min($firstCy, $secondCy);

        return $ix < ($firstDimension[$xAxis] + $secondDimension[$xAxis]) / 2 && $iy < ($firstDimension[$yAxis] + $secondDimension[$yAxis]) / 2;
    }

    /**
     * The two 3d-plane intersection checker.
     * 
     * @param Item $firstItem The first 3d-plane item.
     * @param Item $secondItem The second 3d-plane item.
     * 
     * @return bool The flag indicates whether both items are intersected with each other or not, 
     * return true if both items are intersected with each other, otherwise false.
     */
    public static function isIntersected(Item $firstItem, Item $secondItem): bool
    {
        return (self::isRectangleIntersected($firstItem, $secondItem, AxisType::LENGTH, AxisType::BREADTH) &&
            self::isRectangleIntersected($firstItem, $secondItem, AxisType::BREADTH, AxisType::HEIGHT) &&
            self::isRectangleIntersected($firstItem, $secondItem, AxisType::HEIGHT, AxisType::LENGTH));
    }
}
