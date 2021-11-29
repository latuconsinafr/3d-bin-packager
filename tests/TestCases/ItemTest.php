<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Tests\TestCases;

use Latuconsinafr\BinPackager\BinPackager3D\Item;
use Latuconsinafr\BinPackager\BinPackager3D\Tests\Fixtures\ItemFixture;
use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\RotationCombinationType;
use PHPUnit\Framework\TestCase;

/**
 * Item Test Case.
 */
class ItemTest extends TestCase
{
    /**
     * @var Item The item class.
     */
    private Item $item;

    /**
     * @var ItemFixture The item fixture.
     */
    private ItemFixture $itemFixture;

    /**
     * The set up test environment method.
     * 
     * @return void
     */
    protected function setUp(): void
    {
        $this->itemFixture = new ItemFixture();
        $this->item = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );
    }

    /**
     * getVolume() method tested with item 1 length, height and breadth.
     * 
     * @return void
     */
    public function testGetVolume(): void
    {
        $volume = $this->itemFixture->item1Length * $this->itemFixture->item1Height * $this->itemFixture->item1Breadth;
        $this->assertEquals($volume, $this->item->getVolume());
    }

    /**
     * getDimension() method tested with BLH Rotation
     * which is the length become the breadth (B),
     * the height become the length (L) and
     * the breadth become the height (H).
     * 
     * @return void
     */
    public function testGetDimension(): void
    {
        $this->item->setRotationType(RotationCombinationType::BLH_ROTATION);

        $dimension = $this->item->getDimension();

        $this->assertEquals($dimension[AxisType::LENGTH], $this->item->getBreadth());
        $this->assertEquals($dimension[AxisType::HEIGHT], $this->item->getLength());
        $this->assertEquals($dimension[AxisType::BREADTH], $this->item->getHeight());
    }

    /**
     * setPrecision() method tested with precision value is set to 2.
     * 
     * @return void
     */
    public function testSetPrecision(): void
    {
        $precisionToBe = 2;

        $this->item->setPrecision($precisionToBe);

        $item1HeightString = (string)$this->item->getHeight();
        $precision = (int) strpos(strrev($item1HeightString), ".");

        // The number of digits after the decimal point should be 2
        $this->assertEquals($precisionToBe, $precision);
    }

    /**
     * The tear down test environment method.
     * 
     * @return void
     */
    protected function tearDown(): void
    {
    }
}
