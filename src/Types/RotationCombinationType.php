<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Types;

class RotationCombinationType
{
    public const LBH_ROTATION = 0;
    public const LHB_ROTATION = 1;
    public const BLH_ROTATION = 2;
    public const BHL_ROTATION = 3;
    public const HLB_ROTATION = 4;
    public const HBL_ROTATION = 5;

    public const ALL_ROTATION_COMBINATION = [
        RotationCombinationType::LBH_ROTATION,
        RotationCombinationType::LHB_ROTATION,
        RotationCombinationType::BLH_ROTATION,
        RotationCombinationType::BHL_ROTATION,
        RotationCombinationType::HLB_ROTATION,
        RotationCombinationType::HBL_ROTATION
    ];
}
