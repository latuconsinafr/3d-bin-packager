<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\PivotType;

class Packager
{
    public iterable $bins = [];
    public iterable $items = [];

    public function __construct()
    {
    }

    public function addBin(float $length, float $breadth, float $height, float $weight): void
    {
        $this->bins[] = new Bin($length, $breadth, $height, $weight);
    }

    public function addItem(float $length, float $breadth, float $height, float $weight): void
    {
        $this->items[] = new Item($length, $breadth, $height, $weight);
    }

    public function packToBin(Bin $bin, Item $item): void
    {
        $fitted = false;

        // Bin has no fitted items yet
        if (iterator_count(new \ArrayIterator($bin->fittedItems)) == 0) {
            if (!$bin->putItem($item, PivotType::START_POSITION)) {
                $bin->unfittedItems[] = $item;
            }

            return;
        }

        // Bin has fitted item(s) already
        foreach (range(0, count(AxisType::ALL_AXIS) - 1) as $axis) {
            $fittedItems = $bin->fittedItems;

            foreach ($fittedItems as $fittedItem) {
                $pivot = PivotType::START_POSITION;
                $dimension = $fittedItem->getDimension();

                if ($axis === AxisType::LENGTH) {
                    $pivot = [
                        $fittedItem->position[AxisType::LENGTH] + $dimension[AxisType::LENGTH],
                        $fittedItem->position[AxisType::HEIGHT],
                        $fittedItem->position[AxisType::BREADTH]
                    ];
                } elseif ($axis === AxisType::HEIGHT) {
                    $pivot = [
                        $fittedItem->position[AxisType::LENGTH],
                        $fittedItem->position[AxisType::HEIGHT] + $dimension[AxisType::HEIGHT],
                        $fittedItem->position[AxisType::BREADTH]
                    ];
                } elseif ($axis === AxisType::BREADTH) {
                    $pivot = [
                        $fittedItem->position[AxisType::LENGTH],
                        $fittedItem->position[AxisType::HEIGHT],
                        $fittedItem->position[AxisType::BREADTH] + $dimension[AxisType::BREADTH]
                    ];
                }

                if ($bin->putItem($item, $pivot)) {
                    $fitted = true;
                    break;
                }
            }

            if ($fitted) {
                break;
            }
        }

        if (!$fitted) {
            $bin->unfittedItems[] = $item;
        }
    }

    public function pack()
    {
        foreach ($this->bins as $bin) {
            foreach ($this->items as $item) {
                $this->packToBin($bin, $item);
            }
        }
    }
}
