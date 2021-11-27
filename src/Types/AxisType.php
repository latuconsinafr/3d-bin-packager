<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Types;

/**
 * Enum of possible axises.
 */
class AxisType
{
    // Represents the 3d-plane axis
    public const LENGTH = 'x-axis';
    public const HEIGHT = 'y-axis';
    public const BREADTH = 'z-axis';

    // Enum contains all the 3d-plane axis
    public const ALL_AXIS = [
        AxisType::LENGTH,
        AxisType::HEIGHT,
        AxisType::BREADTH
    ];
}
