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
use Latuconsinafr\BinPackager\BinPackager3D\Types\PositionType;

/**
 * A main packager class to pack all the items into all the bins.
 */
final class Packager implements \JsonSerializable
{
    /**
     * @var iterable The bins.
     */
    private iterable $bins;

    /**
     * @var iterable The items to put into the bins.
     */
    private iterable $items;

    /**
     * @var float The total bins volume inside the packager.
     */
    private float $totalBinsVolume;

    /**
     * @var float The total bins weight inside the packager.
     */
    private float $totalBinsWeight;

    /**
     * @var float The total items volume inside the packager.
     */
    private float $totalItemsVolume;

    /**
     * @var float The total items weight inside the packager.
     */
    private float $totalItemsWeight;

    /**
     */
    public function __construct()
    {
        $this->bins = [];
        $this->items = [];
        $this->totalBinsVolume = 0;
        $this->totalBinsWeight = 0;
        $this->totalItemsVolume = 0;
        $this->totalItemsWeight = 0;
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
     * The packager's iterable bins getter.
     * 
     * @return ArrayIterator The packager's iterable bins.
     */
    public function getIterableBins(): \ArrayIterator
    {
        return new \ArrayIterator($this->bins);
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
     * The packager's iterable items getter.
     * 
     * @return ArrayIterator The packager's iterable items.
     */
    public function getIterableItems(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * The packager's total bin(s) volume getter.
     * 
     * @return float The total bin(s) volume.
     */
    public function getTotalBinsVolume(): float
    {
        return $this->totalBinsVolume;
    }

    /**
     * The packager's total bin(s) weight getter.
     * 
     * @return float The total bin(s) weight.
     */
    public function getTotalBinsWeight(): float
    {
        return $this->totalBinsWeight;
    }

    /**
     * The packager's total item(s) volume getter.
     * 
     * @return float The total item(s) volume.
     */
    public function getTotalItemsVolume(): float
    {
        return $this->totalItemsVolume;
    }

    /**
     * The packager's total item(s) weight getter.
     * 
     * @return float The total item(s) weight.
     */
    public function getTotalItemsWeight(): float
    {
        return $this->totalItemsWeight;
    }

    /**
     * The packager's lower bounds getter.
     * The lower bounds mean that the value returned is the worst case of the bin(s) needed to hold or contain all the items.
     * Calculated based on the bin(s) and item(s) volume.
     * 
     * @return int The lower bounds.
     */
    public function getLowerBounds(): int
    {
        // To prevent the division by zero error
        if ($this->totalItemsVolume == 0) {
            return 0;
        }

        return (int)ceil($this->totalBinsVolume / $this->totalItemsVolume);
    }

    /**
     * The add bin to the packager method.
     * The bin(s) would become the container for the item(s).
     * 
     * @param Bin $bin The bin to contain the item(s).
     * 
     * @return void
     */
    public function addBin(Bin $bin): void
    {
        foreach ($this->bins as $existingBin) {
            if ($existingBin->getId() === $bin->getId()) {
                throw new \UnexpectedValueException("Bin id should be unique.");
            }
        }

        $this->bins[$bin->getId()] = $bin;
        $this->totalBinsVolume += $bin->getVolume();
        $this->totalBinsWeight += $bin->getWeight();
    }

    /**
     * The add bins to the packager method.
     * The bins would become the container for the items.
     * 
     * @param iterable $bins The iterable of @see Bin to contain the item(s).
     * 
     * @return void
     */
    public function addBins(iterable $bins): void
    {
        foreach ($bins as $bin) {
            if (!$bin instanceof Bin) {
                throw new \UnexpectedValueException("Bin should be an instance of Bin class.");
            }

            $this->addBin($bin);
        }
    }

    /**
     * The add item to the packager method.
     * This item(s) to put into the bin(s).
     * 
     * @param Item $item The to put into the bin.
     * 
     * @return void
     */
    public function addItem(Item $item): void
    {
        foreach ($this->items as $existingItem) {
            if ($existingItem->getId() === $item->getId()) {
                throw new \UnexpectedValueException("Item id should be unique.");
            }
        }

        $this->items[$item->getId()] = $item;
        $this->totalItemsVolume += $item->getVolume();
        $this->totalItemsWeight += $item->getWeight();
    }

    /**
     * The add items to the packager method.
     * The items to put into the bin(s).
     * 
     * @param iterable $items The iterable of @see Item to put into the bin(s).
     * 
     * @return void
     */
    public function addItems(iterable $items): void
    {
        foreach ($items as $item) {
            if (!$item instanceof Item) {
                throw new \UnexpectedValueException("Item should be an instance of Item class.");
            }

            $this->addItem($item);
        }
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
        if (iterator_count($bin->getIterableFittedItems()) === 0) {
            if (!$bin->putItem($item, PositionType::START_POSITION)) {
                $bin->setUnfittedItems($item);
            }

            return;
        }

        // Bin has fitted item(s) already
        foreach (AxisType::ALL_AXIS as $axis) {
            $fittedItems = $bin->getFittedItems();

            foreach ($fittedItems as $fittedItem) {
                $pivot = PositionType::START_POSITION;
                $dimension = $fittedItem->getDimension();

                if ($axis === AxisType::LENGTH) {
                    $pivot = [
                        AxisType::LENGTH  => $fittedItem->getPosition()[AxisType::LENGTH] + $dimension[AxisType::LENGTH],
                        AxisType::HEIGHT  => $fittedItem->getPosition()[AxisType::HEIGHT],
                        AxisType::BREADTH => $fittedItem->getPosition()[AxisType::BREADTH]
                    ];
                } elseif ($axis === AxisType::HEIGHT) {
                    $pivot = [
                        AxisType::LENGTH  => $fittedItem->getPosition()[AxisType::LENGTH],
                        AxisType::HEIGHT  => $fittedItem->getPosition()[AxisType::HEIGHT] + $dimension[AxisType::HEIGHT],
                        AxisType::BREADTH  => $fittedItem->getPosition()[AxisType::BREADTH]
                    ];
                } elseif ($axis === AxisType::BREADTH) {
                    $pivot = [
                        AxisType::LENGTH  => $fittedItem->getPosition()[AxisType::LENGTH],
                        AxisType::HEIGHT  => $fittedItem->getPosition()[AxisType::HEIGHT],
                        AxisType::BREADTH  => $fittedItem->getPosition()[AxisType::BREADTH] + $dimension[AxisType::BREADTH]
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
     * The default pack method, keeps all bins open, in the order in which they were opened. 
     * It attempts to place each new item into the first bin in which it fits.
     * 
     * @return self
     */
    public function withFirstFit(): self
    {
        return $this;
    }

    /**
     * Orders the items by descending size in this case is the item volume, then calls First-Fit 
     * (in this case the self pack method).
     * 
     * @return self
     */
    public function withFirstFitDecreasing(): self
    {
        // Sort the bins
        $iterableBins = $this->getIterableBins();
        $iterableBins->uasort(function ($a, $b) {
            if ($a->getVolume() === $b->getVolume()) return 0;
            return ($a->getVolume() > $b->getVolume()) ? -1 : 1;
        });

        $this->bins = $iterableBins;

        // Sort the items
        $iterableItems = $this->getIterableItems();
        $iterableItems->uasort(function ($a, $b) {
            if ($a->getVolume() === $b->getVolume()) return 0;
            return ($a->getVolume() > $b->getVolume()) ? -1 : 1;
        });

        $this->items = $iterableItems;

        return $this;
    }

    /**
     * The main pack method, this method would try to pack all the items into all the bins
     * based on the chosen method, whether the @see withFirstFit() or @see withFirstFitDecreasing().
     * 
     * @return void
     */
    public function pack(): void
    {
        foreach ($this->bins as $bin) {

            // No item left
            if (iterator_count($this->getIterableItems()) === 0) {
                break;
            }

            // Pack item(s) to current open bin
            foreach ($this->items as $item) {
                $this->packItemToBin($bin, $item);
            }

            // Remove the packed item(s)
            foreach ($bin->getFittedItems() as $fittedItem) {
                if ($this->getIterableItems()->offsetExists($fittedItem->getId())) {
                    unset($this->items[$fittedItem->getId()]);
                }
            }
        }
    }

    /**
     * The json serialize method.
     * 
     * @return array The resulted object.
     */
    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
