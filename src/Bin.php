<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

use Latuconsinafr\BinPackager\BinPackager3D\Handlers\Intersectionhandler;
use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\RotationCombinationType;

/**
 * A class representative of a single bin to put @see Item into.
 */
final class Bin
{
    /**
     * @var float The bin's length.
     */
    private float $length;

    /**
     * @var float The bin's breadth.
     */
    private float $breadth;

    /**
     * @var float The bin's height.
     */
    private float $height;

    /**
     * @var float The bin's weight.
     */
    private float $weight;

    /**
     * @var iterable The fitted item(s) inside the bin.
     */
    private iterable $fittedItems;

    /**
     * @var iterable The unfitted item(s).
     */
    private iterable $unfittedItems;

    /**
     * @param float $length The length of the bin.
     * @param float $breadth The breadth of the bin.
     * @param float $height The height of the bin.
     * @param float $weight The weight of the bin.
     */
    public function __construct(float $length, float $breadth, float $height, float $weight)
    {
        $this->length = $length;
        $this->breadth = $breadth;
        $this->height = $height;
        $this->weight = $weight;

        $this->fittedItems = [];
        $this->unfittedItems = [];
    }

    /**
     * The bin's length getter.
     * 
     * @return float The bin's length.
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * The bin's breadth getter.
     * 
     * @return float The bin's breadth.
     */
    public function getBreadth(): float
    {
        return $this->breadth;
    }

    /**
     * The bin's height getter.
     * 
     * @return float The bin's height.
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * The bin's weight getter.
     * 
     * @return float The bin's weight.
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * The bin's fitted items getter.
     * 
     * @return iterable The bin's fitted items.
     */
    public function getFittedItems(): iterable
    {
        return $this->fittedItems;
    }

    /**
     * The bin's unfitted items getter.
     * 
     * @return iterable The bin's unfitted items.
     */
    public function getUnfittedItems(): iterable
    {
        return $this->unfittedItems;
    }

    /**
     * Get the bin's volume.
     * 
     * @return float The bin's volume.
     */
    public function getVolume(): float
    {
        return (float)($this->length * $this->breadth * $this->height);
    }

    /**
     * Get the bin's total weight.
     * 
     * @return float The bin's total weight.
     */
    public function getTotalWeight(): float
    {
        $weight = 0;

        foreach ($this->fittedItems as $item) {
            if (!$item instanceof Item) {
                throw new \UnexpectedValueException("Item should be an instance of Item class.");
            }

            $weight += $item->getWeight();
        }

        return $weight;
    }

    /**
     * The bin's fitted items setter.
     * The parameter value should be an instance of Item class.
     * 
     * @param array $position The item to put into.
     * 
     * @return void
     */
    public function setFittedItems(Item $item): void
    {
        if (!$item instanceof Item) {
            throw new \UnexpectedValueException("Item should be an instance of Item class.");
        }

        $this->fittedItems[] = $item;
    }

    /**
     * The bin's unfitted items setter.
     * The parameter value should be an instance of Item class.
     * 
     * @param array $position The items that cannot be fitted into.
     * 
     * @return void
     */
    public function setUnfittedItems(Item $item): void
    {
        if (!$item instanceof Item) {
            throw new \UnexpectedValueException("Item should be an instance of Item class.");
        }

        $this->unfittedItems[] = $item;
    }

    /**
     * The put item into the bin method.
     * 
     * @param Item $item The item to put into.
     * @param array $position The starting position.
     * 
     * @return bool The flag indicates whether the item can fit into the bin or not, 
     * return true if the item can fit into the bin, otherwise false.
     */
    public function putItem(Item $item, array $position): bool
    {
        $fit = false;
        $validItemPosition = $item->getPosition();
        $item->setPosition($position);

        foreach (range(0, count(RotationCombinationType::ALL_ROTATION_COMBINATION) - 1) as $rotationType) {
            $item->setRotationType($rotationType);
            $dimension = $item->getDimension();

            if (
                $this->length < $position[AxisType::LENGTH] + $dimension[AxisType::LENGTH] ||
                $this->height < $position[AxisType::HEIGHT] + $dimension[AxisType::HEIGHT] ||
                $this->breadth < $position[AxisType::BREADTH] + $dimension[AxisType::BREADTH]
            ) {
                continue;
            }

            $fit = true;

            foreach ($this->fittedItems as $fitted_item) {
                if (IntersectionHandler::isIntersected($fitted_item, $item)) {
                    $fit = false;

                    break;
                }
            }

            if ($fit) {
                if (($this->getTotalWeight() + $item->getWeight()) > $this->weight) {
                    return false;
                }

                $this->setFittedItems($item);
            }

            if (!$fit) {
                $item->setPosition($validItemPosition);
            }

            return $fit;
        }

        if (!$fit) {
            $item->setPosition($validItemPosition);
        }

        return $fit;
    }
}
