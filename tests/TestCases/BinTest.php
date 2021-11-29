<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Tests\TestCases;

use Latuconsinafr\BinPackager\BinPackager3D\Bin;
use Latuconsinafr\BinPackager\BinPackager3D\Item;
use Latuconsinafr\BinPackager\BinPackager3D\Tests\Fixtures\BinFixture;
use Latuconsinafr\BinPackager\BinPackager3D\Tests\Fixtures\ItemFixture;
use Latuconsinafr\BinPackager\BinPackager3D\Types\PositionType;
use PHPUnit\Framework\TestCase;

/**
 * Bin Test Case.
 */
class BinTest extends TestCase
{
    /**
     * @var Bin The bin class.
     */
    private Bin $bin;

    /**
     * @var BinFixture The bin fixture.
     */
    private BinFixture $binFixture;

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
        $this->binFixture = new BinFixture();
        $this->itemFixture = new ItemFixture();
        $this->bin = new Bin(
            $this->binFixture->bin1Id,
            $this->binFixture->bin1Length,
            $this->binFixture->bin1Height,
            $this->binFixture->bin1Breadth,
            $this->binFixture->bin1Weight,
        );
    }

    /**
     * getVolume() method tested with item 1 length, height and breadth.
     * 
     * @return void
     */
    public function testGetVolume(): void
    {
        $volume = $this->binFixture->bin1Length * $this->binFixture->bin1Height * $this->binFixture->bin1Breadth;
        $this->assertEquals($volume, $this->bin->getVolume());
    }

    /**
     * setPrecision() method tested with precision value is set to 2.
     * 
     * @return void
     */
    public function testSetPrecision(): void
    {
        $precisionToBe = 2;

        $this->bin->setPrecision($precisionToBe);

        $bin1LengthString = (string)$this->bin->getLength();
        $precision = (int) strpos(strrev($bin1LengthString), ".");

        // The number of digits after the decimal point should be 2
        $this->assertEquals($precisionToBe, $precision);
    }

    /**
     * setFittedItems() method tested with two items.
     * 
     * @return void
     */
    public function testSetFittedItems(): void
    {
        $item1 = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );

        $item2 = new Item(
            $this->itemFixture->item2Id,
            $this->itemFixture->item2Length,
            $this->itemFixture->item2Height,
            $this->itemFixture->item2Breadth,
            $this->itemFixture->item2Weight,
        );

        $this->bin->setFittedItems($item1);
        $this->bin->setFittedItems($item2);

        $totalFittedVolume = $item1->getVolume() + $item2->getVolume();
        $totalFittedWeight = $item1->getWeight() + $item2->getWeight();

        $this->assertCount(2, $this->bin->getFittedItems());
        $this->assertEquals($totalFittedVolume, $this->bin->getTotalFittedVolume());
        $this->assertEquals($totalFittedWeight, $this->bin->getTotalFittedWeight());
    }

    /**
     * setUnfittedItems() method tested with two items.
     * 
     * @return void
     */
    public function testSetUnfittedItems(): void
    {
        $item1 = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );

        $item2 = new Item(
            $this->itemFixture->item2Id,
            $this->itemFixture->item2Length,
            $this->itemFixture->item2Height,
            $this->itemFixture->item2Breadth,
            $this->itemFixture->item2Weight,
        );

        $this->bin->setUnfittedItems($item1);
        $this->bin->setUnfittedItems($item2);

        $totalUnfittedVolume = $item1->getVolume() + $item2->getVolume();
        $totalUnfittedWeight = $item1->getWeight() + $item2->getWeight();

        $this->assertCount(2, $this->bin->getUnfittedItems());
        $this->assertEquals($totalUnfittedVolume, $this->bin->getTotalUnfittedVolume());
        $this->assertEquals($totalUnfittedWeight, $this->bin->getTotalUnfittedWeight());
    }

    /**
     * putItem() method tested with one item which
     * has the fitted length, height, breadth and weight compare to
     * the bin's length, height, breadth and weight and any other fitted item(s) inside the bin.
     * In this case, the bin has no fitted item(s) yet.
     * 
     * @return void
     */
    public function testPutItem_Fitted(): void
    {
        $item = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );

        $result = $this->bin->putItem($item, PositionType::START_POSITION);

        $this->assertTrue($result);
        $this->assertCount(1, $this->bin->getFittedItems());
        $this->assertEquals($this->itemFixture->item1Id, $this->bin->getFittedItems()[0]->getId());
    }

    /**
     * putItem() method tested with one item which
     * has the unfitted length, height, breadth and weight compare to
     * the bin's length, height, breadth and weight.
     * 
     * @return void
     */
    public function testPutItem_Unfitted_Overload(): void
    {
        $item = new Item(
            $this->itemFixture->item10Id,
            $this->itemFixture->item10Length,
            $this->itemFixture->item10Height,
            $this->itemFixture->item10Breadth,
            $this->itemFixture->item10Weight,
        );

        $result = $this->bin->putItem($item, PositionType::START_POSITION);

        $this->assertFalse($result);
    }

    /**
     * putItem() method tested with two items which
     * has the fitted length, height, breadth and weight compare to
     * the bin's length, height, breadth and weight and any other fitted item(s) inside the bin.
     * But in this case, both items has same pivot position.
     * 
     * @return void
     */
    public function testPutItem_Unfitted_With_Items_Intersected(): void
    {
        $item1 = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );
        $item2 = new Item(
            $this->itemFixture->item2Id,
            $this->itemFixture->item2Length,
            $this->itemFixture->item2Height,
            $this->itemFixture->item2Breadth,
            $this->itemFixture->item2Weight,
        );

        $result = $this->bin->putItem($item1, PositionType::START_POSITION);
        $result = $this->bin->putItem($item2, PositionType::START_POSITION);

        $this->assertFalse($result);
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
