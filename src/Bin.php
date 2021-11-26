<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

use Latuconsinafr\BinPackager\BinPackager3D\Handlers\Intersectionhandler;
use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\RotationCombinationType;

class Bin
{
    public float $length;
    public float $breadth;
    public float $height;
    public float $weight;
    public iterable $fittedItems = [];
    public iterable $unfittedItems = [];

    public function __construct(float $length, float $breadth, float $height, float $weight)
    {
        $this->length = $length;
        $this->breadth = $breadth;
        $this->height = $height;
        $this->weight = $weight;
    }

    public function getVolume(): float
    {
        return (float)($this->length * $this->breadth * $this->height);
    }

    public function getTotalWeight(): float
    {
        $weight = 0;

        foreach ($this->fittedItems as $item) {
            $weight += $item->weight;
        }

        return $weight;
    }

    public function putItem(Item $item, array $pivot): bool
    {
        $fit = false;
        $valid_item_position = $item->position;
        $item->position = $pivot;

        foreach (range(0, count(RotationCombinationType::ALL_ROTATION_COMBINATION) - 1) as $rotationType) {
            $item->rotationType = $rotationType;
            $dimension = $item->getDimension();

            if (
                $this->length < $pivot[AxisType::LENGTH] + $dimension[AxisType::LENGTH] ||
                $this->height < $pivot[AxisType::HEIGHT] + $dimension[AxisType::HEIGHT] ||
                $this->breadth < $pivot[AxisType::BREADTH] + $dimension[AxisType::BREADTH]
            ) {
                continue;
            }

            $fit = true;

            foreach ($this->fittedItems as $fitted_item) {
                if (IntersectionHandler::isIntersected($item, $fitted_item)) {
                    $fit = false;
                    break;
                }
            }

            if ($fit) {
                if ($this->getTotalWeight() + $item->weight > $this->weight) {
                    $fit = false;

                    return $fit;
                }

                $this->fittedItems[] = $item;
            }

            if (!$fit) {
                $item->position = $valid_item_position;
            }

            return $fit;
        }

        if (!$fit) {
            $item->position = $valid_item_position;
        }

        return $fit;
    }
}
