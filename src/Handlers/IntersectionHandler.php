<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Handlers;

use Latuconsinafr\BinPackager\BinPackager3D\Item;
use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;

final class IntersectionHandler
{
    private static function isRectangleIntersected(Item $firstItem, Item $secondItem, int $xAxis, int $yAxis): bool
    {
        $firstDimension = $firstItem->getDimension();
        $secondDimension = $secondItem->getDimension();

        $firstCx = $firstItem->position[$xAxis] + $firstDimension[$xAxis] / 2;
        $firstCy = $firstItem->position[$yAxis] + $firstDimension[$yAxis] / 2;

        $secondCx = $secondItem->position[$xAxis] + $secondDimension[$xAxis] / 2;
        $secondCy = $secondItem->position[$yAxis] + $secondDimension[$yAxis] / 2;

        $ix = max($firstCx, $secondCx) - min($firstCx, $secondCx);
        $iy = max($firstCy, $secondCy) - min($firstCy, $secondCy);

        return $ix < ($firstDimension[$xAxis] + $secondDimension[$xAxis]) / 2 && $iy < ($firstDimension[$yAxis] + $secondDimension[$yAxis]) / 2;
    }

    public static function isIntersected(Item $firstItem, Item $secondItem): bool
    {
        return (self::isRectangleIntersected($firstItem, $secondItem, AxisType::LENGTH, AxisType::BREADTH) &&
            self::isRectangleIntersected($firstItem, $secondItem, AxisType::BREADTH, AxisType::HEIGHT) &&
            self::isRectangleIntersected($firstItem, $secondItem, AxisType::HEIGHT, AxisType::LENGTH));
    }
}
