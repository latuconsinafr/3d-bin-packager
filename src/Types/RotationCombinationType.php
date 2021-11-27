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
 * Enum of rotation combinations.
 */
class RotationCombinationType
{
    // Rotation based on length (x-axis)
    public const LHB_ROTATION = 0;
    public const LBH_ROTATION = 1;

    // Rotation based on height (y-axis)
    public const HLB_ROTATION = 2;
    public const HBL_ROTATION = 3;

    // Rotation based on breadth (z-axis)
    public const BLH_ROTATION = 4;
    public const BHL_ROTATION = 5;

    // Enum contains all the rotation combination type
    public const ALL_ROTATION_COMBINATION = [
        RotationCombinationType::LHB_ROTATION,
        RotationCombinationType::LBH_ROTATION,
        RotationCombinationType::HLB_ROTATION,
        RotationCombinationType::HBL_ROTATION,
        RotationCombinationType::BLH_ROTATION,
        RotationCombinationType::BHL_ROTATION
    ];
}
