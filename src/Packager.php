<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\PivotType;

/**
 * A main packager class to pack all the items into all the bins.
 */
final class Packager
{
    /**
     * @var iterable The bins.
     */
    private iterable $bins = [];

    /**
     * @var iterable The items to put into the bins.
     */
    private iterable $items = [];

    /**
     */
    public function __construct()
    {
        $this->bins = [];
        $this->items = [];
    }

    /**
     * The packager's bins getter.
     * 
     * @return iterable The packager's bins.
     */
    public function getBins(): iterable
    {
        return $this->bins;
    }

    /**
     * The packager's items getter.
     * 
     * @return iterable The packager's items.
     */
    public function getItems(): iterable
    {
        return $this->items;
    }

    /**
     * The add bin to the packager method.
     * The bin(s) would become the container for all the items.
     * 
     * @param float $length The length of the bin.
     * @param float $breadth The breadth of the bin.
     * @param float $height The height of the bin.
     * @param float $weight The weight of the bin.
     * 
     * @return void
     */
    public function addBin(float $length, float $breadth, float $height, float $weight): void
    {
        $this->bins[] = new Bin($length, $breadth, $height, $weight);
    }

    /**
     * The add item to the packager method.
     * This item(s) to put into the bin.
     * 
     * @param float $length The length of the bin.
     * @param float $breadth The breadth of the bin.
     * @param float $height The height of the bin.
     * @param float $weight The weight of the bin.
     * 
     * @return void
     */
    public function addItem(float $length, float $breadth, float $height, float $weight): void
    {
        $this->items[] = new Item($length, $breadth, $height, $weight);
    }

    /**
     * The pack item to bin method.
     * This method would try to pack the inputted item into the inputted bin.
     * Whether the inputted item would fit into the inputted bin or not.
     * 
     * @param Bin $bin The bin to put the item into.
     * @param Item $item The item to put into the bin.
     * 
     * @return void
     */
    public function packItemToBin(Bin $bin, Item $item): void
    {
        $fitted = false;

        if (!$bin instanceof Bin) {
            throw new \UnexpectedValueException("Bin should be an instance of Bin class.");
        }
        if (!$item instanceof Item) {
            throw new \UnexpectedValueException("Item should be an instance of Item class.");
        }

        // Bin has no fitted items yet
        if (iterator_count(new \ArrayIterator($bin->getFittedItems())) === 0) {
            if (!$bin->putItem($item, PivotType::START_POSITION)) {
                $bin->setUnfittedItems($item);
            }

            return;
        }

        // Bin has fitted item(s) already
        foreach (range(0, count(AxisType::ALL_AXIS) - 1) as $axis) {
            $fittedItems = $bin->getFittedItems();

            foreach ($fittedItems as $fittedItem) {
                $pivot = PivotType::START_POSITION;
                $dimension = $fittedItem->getDimension();

                if ($axis === AxisType::LENGTH) {
                    $pivot = [
                        $fittedItem->getPosition()[AxisType::LENGTH] + $dimension[AxisType::LENGTH],
                        $fittedItem->getPosition()[AxisType::HEIGHT],
                        $fittedItem->getPosition()[AxisType::BREADTH]
                    ];
                } elseif ($axis === AxisType::HEIGHT) {
                    $pivot = [
                        $fittedItem->getPosition()[AxisType::LENGTH],
                        $fittedItem->getPosition()[AxisType::HEIGHT] + $dimension[AxisType::HEIGHT],
                        $fittedItem->getPosition()[AxisType::BREADTH]
                    ];
                } elseif ($axis === AxisType::BREADTH) {
                    $pivot = [
                        $fittedItem->getPosition()[AxisType::LENGTH],
                        $fittedItem->getPosition()[AxisType::HEIGHT],
                        $fittedItem->getPosition()[AxisType::BREADTH] + $dimension[AxisType::BREADTH]
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
            $bin->setUnfittedItems($item);
        }
    }

    /**
     * The main pack method, this method would try to pack all the items into all the bins.
     * 
     * @return void
     */
    public function pack(): void
    {
        foreach ($this->bins as $bin) {
            foreach ($this->items as $item) {
                $this->packItemToBin($bin, $item);
            }
        }
    }
}
