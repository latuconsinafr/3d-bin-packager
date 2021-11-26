<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

use Latuconsinafr\BinPackager\BinPackager3D\Types\PivotType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\RotationCombinationType;

/**
 * A class representative of a single item to put into the @see Bin.
 */
final class Item
{
    /**
     * @var float The item's length.
     */
    private float $length;

    /**
     * @var float The item's breadth.
     */
    private float $breadth;

    /**
     * @var float The item's height.
     */
    private float $height;

    /**
     * @var float The item's weight.
     */
    private float $weight;

    /**
     * @var int The item's current rotation type.
     */
    private int $rotationType;

    /**
     * @var array The item's current position.
     */
    private array $position;

    /**
     * @param float $length The length of the item.
     * @param float $breadth The breadth of the item.
     * @param float $height The height of the item.
     * @param float $weight The weight of the item.
     */
    public function __construct(float $length, float $breadth, float $height, float $weight)
    {
        $this->length = $length;
        $this->breadth = $breadth;
        $this->height = $height;
        $this->weight = $weight;

        $this->rotationType = RotationCombinationType::LBH_ROTATION;
        $this->position = PivotType::START_POSITION;
    }

    /**
     * The item's length getter.
     * 
     * @return float The item's length.
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * The item's breadth getter.
     * 
     * @return float The item's breadth.
     */
    public function getBreadth(): float
    {
        return $this->breadth;
    }

    /**
     * The item's height getter.
     * 
     * @return float The item's height.
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * The item's weight getter.
     * 
     * @return float The item's weight.
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * The item's rotation type getter.
     * 
     * @return int The item's rotation type.
     */
    public function getRotationType(): int
    {
        return $this->rotationType;
    }

    /**
     * The item's position type getter.
     * In this case, it would return an array of 3 values representations of the x-axis, y-axis and z-axis (3d plane).
     * 
     * @return int The item's position (for example = [0, 0, 0]).
     */
    public function getPosition(): array
    {
        return $this->position;
    }

    /**
     * Get the item's volume.
     * 
     * @return float The item's volume.
     */
    public function getVolume(): float
    {
        return (float)($this->length * $this->breadth * $this->height);
    }

    /**
     * Get the item's dimension based on the rotation type.
     * In this case, it would return an array of 3 values representations of the x-axis, y-axis and z-axis (3d plane).
     * 
     * @return array The item's dimension (for example = [0, 0, 0]).
     */
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

    /**
     * The item's rotation type setter.
     * The parameter value should be in range between 0 and 5.
     * 
     * @param int $rotationType The item's rotation type, @see RotationCombinationType for the rotation type list.
     * 
     * @return void
     */
    public function setRotationType(int $rotationType): void
    {
        if (!is_int($rotationType) || $rotationType < 0 || $rotationType > 5) {
            throw new \UnexpectedValueException("Invalid rotation combination type value, the value should be in between 0 and 5.");
        }

        $this->rotationType = $rotationType;
    }

    /**
     * The item's position setter.
     * The parameter value should be an array consisting of 3 values representing the x-axis, y-axis and z-axis.
     * 
     * @param array $position The item's position.
     * 
     * @return void
     */
    public function setPosition(array $position): void
    {
        if (!is_array($position) || count($position) != 3) {
            throw new \UnexpectedValueException("Invalid position value, the value should be an array consisting of 3 values representing the x-axis, y-axis and z-axis.");
        }

        $this->position = $position;
    }
}
