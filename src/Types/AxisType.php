<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Types;

class AxisType
{
    public const LENGTH = 0;
    public const HEIGHT = 1;
    public const BREADTH = 2;

    public const ALL_AXIS = [
        AxisType::LENGTH,
        AxisType::HEIGHT,
        AxisType::BREADTH
    ];
}
