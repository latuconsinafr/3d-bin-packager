<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Types;

/**
 * Enum of possible axises.
 */
class AxisType
{
    // Represents the 3d-plane axis
    public const LENGTH = 0;
    public const HEIGHT = 1;
    public const BREADTH = 2;

    // Enum contains all the 3d-plane axis
    public const ALL_AXIS = [
        AxisType::LENGTH,
        AxisType::HEIGHT,
        AxisType::BREADTH
    ];
}
