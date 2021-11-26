<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

use Latuconsinafr\BinPackager\BinPackager3D\Types\PivotType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\RotationCombinationType;

class Item
{
    public float $length;
    public float $breadth;
    public float $height;
    public float $weight;
    public int $rotationType;
    public array $position;

    public function __construct(float $length, float $breadth, float $height, float $weight)
    {
        $this->length = $length;
        $this->breadth = $breadth;
        $this->height = $height;
        $this->weight = $weight;

        $this->rotationType = RotationCombinationType::LBH_ROTATION;
        $this->position = PivotType::START_POSITION;
    }

    public function getVolume(): float
    {
        return (float)($this->length * $this->breadth * $this->height);
    }

    public function getDimension(): array
    {
        switch ($this->rotationType) {
            case RotationCombinationType::LBH_ROTATION:
                return [$this->length, $this->breadth, $this->height];
                break;

            case RotationCombinationType::LHB_ROTATION:
                return [$this->length, $this->height, $this->breadth];
                break;

            case RotationCombinationType::BLH_ROTATION:
                return [$this->breadth, $this->length, $this->height];
                break;

            case RotationCombinationType::BHL_ROTATION:
                return [$this->breadth, $this->height, $this->length];
                break;

            case RotationCombinationType::HLB_ROTATION:
                return [$this->height, $this->length, $this->breadth];
                break;

            case RotationCombinationType::HBL_ROTATION:
                return [$this->height, $this->breadth, $this->length];
                break;
            default:
                throw new \UnexpectedValueException("Invalid rotation combination type, the value should be in between 0 and 5.");
        }
    }
}
