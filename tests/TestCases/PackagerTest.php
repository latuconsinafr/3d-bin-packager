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
use Latuconsinafr\BinPackager\BinPackager3D\Packager;
use Latuconsinafr\BinPackager\BinPackager3D\Tests\Fixtures\BinFixture;
use Latuconsinafr\BinPackager\BinPackager3D\Tests\Fixtures\ItemFixture;
use Latuconsinafr\BinPackager\BinPackager3D\Types\SortType;
use PHPUnit\Framework\TestCase;

/**
 * Packager Test Case.
 */
class PackagerTest extends TestCase
{
    /**
     * @var Packager The packager class.
     */
    private Packager $packager;

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
        $this->packager = new Packager(2, -1);
        $this->binFixture = new BinFixture();
        $this->itemFixture = new ItemFixture();
    }

    /**
     * construct() method tested with invalid precision value.
     * 
     * @return void
     */
    public function testConstructor_Invalid_Precision_Value(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("The number of digits should be more than or equals to zero.");

        $this->packager = new Packager(-999, SortType::DESCENDING);
    }

    /**
     * construct() method tested with invalid sort method value.
     * 
     * @return void
     */
    public function testConstructor_Invalid_SortMethod_Value(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("The sort method should be either 1 (for ascending) or -1 (for descending).");

        $this->packager = new Packager(1, 3);
    }

    /**
     * getLowerBounds() method tested with two bins and 5 items.
     * with all 5 items should be fitted (the worst case) into one bin. 
     * 
     * @return void
     */
    public function testGetLowerBounds(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
        ]);

        $lowerBounds = $this->packager->getLowerBounds();
        $this->assertEquals($lowerBounds, 1);
    }

    /**
     * getTotalBinsVolume() method tested with two bins.
     * 
     * @return void
     */
    public function testGetTotalBinsVolume(): void
    {
        $bin1 = new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight);
        $bin2 = new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight);
        $this->packager->addBins([
            $bin1,
            $bin2,
        ]);

        $totalBinsVolume = $this->packager->getTotalBinsVolume();
        $actualTotalBinsVolume = $bin1->getVolume() + $bin2->getVolume();

        $this->assertEquals($totalBinsVolume, $actualTotalBinsVolume);
    }

    /**
     * getTotalBinsWeight() method tested with two bins.
     * 
     * @return void
     */
    public function testGetTotalBinsWeight(): void
    {
        $bin1 = new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight);
        $bin2 = new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight);
        $this->packager->addBins([
            $bin1,
            $bin2,
        ]);

        $totalBinsWeight = $this->packager->getTotalBinsWeight();
        $actualTotalBinsWeight = $bin1->getWeight() + $bin2->getWeight();

        $this->assertEquals($totalBinsWeight, $actualTotalBinsWeight);
    }

    /**
     * getTotalItemsVolume() method tested with five items.
     * 
     * @return void
     */
    public function testGetTotalItemsVolume(): void
    {
        $item1 = new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight);
        $item2 = new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight);
        $item3 = new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight);
        $item4 = new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight);
        $item5 = new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight);

        $this->packager->addItems([
            $item1,
            $item2,
            $item3,
            $item4,
            $item5
        ]);

        $totalItemsVolume = $this->packager->getTotalItemsVolume();
        $actualTotalItemsVolume = $item1->getVolume() + $item2->getVolume() + $item3->getVolume() + $item4->getVolume() + $item5->getVolume();

        $this->assertEquals($totalItemsVolume, $actualTotalItemsVolume);
    }

    /**
     * getTotalItemsWeight() method tested with five items.
     * 
     * @return void
     */
    public function testGetTotalItemsWeight(): void
    {
        $item1 = new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight);
        $item2 = new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight);
        $item3 = new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight);
        $item4 = new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight);
        $item5 = new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight);

        $this->packager->addItems([
            $item1,
            $item2,
            $item3,
            $item4,
            $item5
        ]);

        $totalItemsWeight = $this->packager->getTotalItemsWeight();
        $actualTotalItemsWeight = $item1->getWeight() + $item2->getWeight() + $item3->getWeight() + $item4->getWeight() + $item5->getWeight();

        $this->assertEquals($totalItemsWeight, $actualTotalItemsWeight);
    }

    /**
     * addBins() method tested with bins that has unique identifier.
     * 
     * @return void
     */
    public function testAddBins_Unique_Bins_Identifier(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->assertCount(2, $this->packager->getBins());
    }

    /**
     * addBins() method tested with bins that has duplicate identifier.
     * 
     * @return void
     */
    public function testAddBins_Duplicate_Bins_Identifier(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Bin id should be unique.");

        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);
    }

    /**
     * addItems() method tested with items that has unique identifier.
     * 
     * @return void
     */
    public function testAddItems_Unique_Items_Identifier(): void
    {
        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
        ]);

        $this->assertCount(5, $this->packager->getItems());
    }

    /**
     * addItems() method tested with items that has duplicate identifier.
     * 
     * @return void
     */
    public function testAddItems_Duplicate_Items_Identifier(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage("Item id should be unique.");

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item1Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
        ]);
    }

    /**
     * pack() method tested with two bins and seven items.
     * The second bin should go first, cause it has bigger volume.
     * All items are fitted into just only the second bin.
     * 
     * @return void
     */
    public function testPackMethod_All_Items_Fitted_Into_One_Bin(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
            new Item($this->itemFixture->item6Id, $this->itemFixture->item6Length, $this->itemFixture->item6Height, $this->itemFixture->item6Breadth, $this->itemFixture->item6Weight),
            new Item($this->itemFixture->item7Id, $this->itemFixture->item7Length, $this->itemFixture->item7Height, $this->itemFixture->item7Breadth, $this->itemFixture->item7Weight),
        ]);

        $this->packager->withFirstFit()->pack();

        $resultedBins = $this->packager->getBins();

        $this->assertCount(7, $resultedBins[$this->binFixture->bin2Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin2Id]->getUnfittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getUnfittedItems());
    }

    /**
     * pack() method tested with two bins and nine items.
     * The second bin should go first, cause it has bigger volume.
     * Eight items are fitted into the second bin, and the rest would be fitted into the first bin.
     * 
     * @return void
     */
    public function testPackMethod_All_Items_Fitted_Into_Two_Bins(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
            new Item($this->itemFixture->item6Id, $this->itemFixture->item6Length, $this->itemFixture->item6Height, $this->itemFixture->item6Breadth, $this->itemFixture->item6Weight),
            new Item($this->itemFixture->item7Id, $this->itemFixture->item7Length, $this->itemFixture->item7Height, $this->itemFixture->item7Breadth, $this->itemFixture->item7Weight),
            new Item($this->itemFixture->item8Id, $this->itemFixture->item8Length, $this->itemFixture->item8Height, $this->itemFixture->item8Breadth, $this->itemFixture->item8Weight),
            new Item($this->itemFixture->item9Id, $this->itemFixture->item9Length, $this->itemFixture->item9Height, $this->itemFixture->item9Breadth, $this->itemFixture->item9Weight),
        ]);

        $this->packager->withFirstFit()->pack();

        $resultedBins = $this->packager->getBins();

        $this->assertCount(8, $resultedBins[$this->binFixture->bin2Id]->getFittedItems());
        $this->assertCount(1, $resultedBins[$this->binFixture->bin1Id]->getFittedItems());
        $this->assertCount(1, $resultedBins[$this->binFixture->bin2Id]->getUnfittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getUnfittedItems());
    }

    /**
     * pack() method tested with two bins and ten items.
     * The second bin should go first, cause it has bigger volume.
     * Eight items are fitted into the second bin, and only one is fitted into the first bin,
     * since the item 10 is not fitted into any bin.
     * 
     * @return void
     */
    public function testPackMethod_Not_All_Items_Fitted_Into_Two_Bins(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
            new Item($this->itemFixture->item6Id, $this->itemFixture->item6Length, $this->itemFixture->item6Height, $this->itemFixture->item6Breadth, $this->itemFixture->item6Weight),
            new Item($this->itemFixture->item7Id, $this->itemFixture->item7Length, $this->itemFixture->item7Height, $this->itemFixture->item7Breadth, $this->itemFixture->item7Weight),
            new Item($this->itemFixture->item8Id, $this->itemFixture->item8Length, $this->itemFixture->item8Height, $this->itemFixture->item8Breadth, $this->itemFixture->item8Weight),
            new Item($this->itemFixture->item9Id, $this->itemFixture->item9Length, $this->itemFixture->item9Height, $this->itemFixture->item9Breadth, $this->itemFixture->item9Weight),
            new Item($this->itemFixture->item10Id, $this->itemFixture->item10Length, $this->itemFixture->item10Height, $this->itemFixture->item10Breadth, $this->itemFixture->item10Weight),
        ]);

        $this->packager->withFirstFit()->pack();

        $resultedBins = $this->packager->getBins();

        $this->assertCount(8, $resultedBins[$this->binFixture->bin2Id]->getFittedItems());
        $this->assertCount(1, $resultedBins[$this->binFixture->bin1Id]->getFittedItems());
        $this->assertCount(2, $resultedBins[$this->binFixture->bin2Id]->getUnfittedItems());
        $this->assertCount(1, $resultedBins[$this->binFixture->bin1Id]->getUnfittedItems());
    }

    /**
     * pack() method tested with two bins and seven items.
     * The second bin should go first, cause it has bigger volume.
     * All items are fitted into just only the second bin.
     *
     * @return void
     */
    public function testPackMethod_HeaviestFirst_All_Items_Fitted_Into_One_Bin(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
            new Item($this->itemFixture->item6Id, $this->itemFixture->item6Length, $this->itemFixture->item6Height, $this->itemFixture->item6Breadth, $this->itemFixture->item6Weight),
            new Item($this->itemFixture->item7Id, $this->itemFixture->item7Length, $this->itemFixture->item7Height, $this->itemFixture->item7Breadth, $this->itemFixture->item7Weight),
        ]);

        $this->packager->withHeaviestFirst()->pack();

        $resultedBins = $this->packager->getBins();

        $this->assertCount(7, $resultedBins[$this->binFixture->bin2Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin2Id]->getUnfittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getUnfittedItems());
    }

    /**
     * pack() method tested with two bins and nine items.
     * The second bin should go first, cause it has bigger volume.
     * All items are fitted into the second bin.
     *
     * @return void
     */
    public function testPackMethod_HeaviestFirst_All_Items_Fitted_Into_Two_Bins(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
            new Item($this->itemFixture->item6Id, $this->itemFixture->item6Length, $this->itemFixture->item6Height, $this->itemFixture->item6Breadth, $this->itemFixture->item6Weight),
            new Item($this->itemFixture->item7Id, $this->itemFixture->item7Length, $this->itemFixture->item7Height, $this->itemFixture->item7Breadth, $this->itemFixture->item7Weight),
            new Item($this->itemFixture->item8Id, $this->itemFixture->item8Length, $this->itemFixture->item8Height, $this->itemFixture->item8Breadth, $this->itemFixture->item8Weight),
            new Item($this->itemFixture->item9Id, $this->itemFixture->item9Length, $this->itemFixture->item9Height, $this->itemFixture->item9Breadth, $this->itemFixture->item9Weight),
        ]);

        $this->packager->withHeaviestFirst()->pack();

        $resultedBins = $this->packager->getBins();

        $this->assertCount(9, $resultedBins[$this->binFixture->bin2Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin2Id]->getUnfittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getUnfittedItems());
    }

    /**
     * pack() method tested with two bins and ten items.
     * The second bin should go first, cause it has bigger volume.
     * Eight items are fitted into the second bin, and only one is fitted into the first bin,
     * since the item 10 is not fitted into any bin.
     *
     * @return void
     */
    public function testPackMethod_HeaviestFirst_Not_All_Items_Fitted_Into_Two_Bins(): void
    {
        $this->packager->addBins([
            new Bin($this->binFixture->bin1Id, $this->binFixture->bin1Length, $this->binFixture->bin1Height, $this->binFixture->bin1Breadth, $this->binFixture->bin1Weight),
            new Bin($this->binFixture->bin2Id, $this->binFixture->bin2Length, $this->binFixture->bin2Height, $this->binFixture->bin2Breadth, $this->binFixture->bin2Weight),
        ]);

        $this->packager->addItems([
            new Item($this->itemFixture->item1Id, $this->itemFixture->item1Length, $this->itemFixture->item1Height, $this->itemFixture->item1Breadth, $this->itemFixture->item1Weight),
            new Item($this->itemFixture->item2Id, $this->itemFixture->item2Length, $this->itemFixture->item2Height, $this->itemFixture->item2Breadth, $this->itemFixture->item2Weight),
            new Item($this->itemFixture->item3Id, $this->itemFixture->item3Length, $this->itemFixture->item3Height, $this->itemFixture->item3Breadth, $this->itemFixture->item3Weight),
            new Item($this->itemFixture->item4Id, $this->itemFixture->item4Length, $this->itemFixture->item4Height, $this->itemFixture->item4Breadth, $this->itemFixture->item4Weight),
            new Item($this->itemFixture->item5Id, $this->itemFixture->item5Length, $this->itemFixture->item5Height, $this->itemFixture->item5Breadth, $this->itemFixture->item5Weight),
            new Item($this->itemFixture->item6Id, $this->itemFixture->item6Length, $this->itemFixture->item6Height, $this->itemFixture->item6Breadth, $this->itemFixture->item6Weight),
            new Item($this->itemFixture->item7Id, $this->itemFixture->item7Length, $this->itemFixture->item7Height, $this->itemFixture->item7Breadth, $this->itemFixture->item7Weight),
            new Item($this->itemFixture->item8Id, $this->itemFixture->item8Length, $this->itemFixture->item8Height, $this->itemFixture->item8Breadth, $this->itemFixture->item8Weight),
            new Item($this->itemFixture->item9Id, $this->itemFixture->item9Length, $this->itemFixture->item9Height, $this->itemFixture->item9Breadth, $this->itemFixture->item9Weight),
            new Item($this->itemFixture->item10Id, $this->itemFixture->item10Length, $this->itemFixture->item10Height, $this->itemFixture->item10Breadth, $this->itemFixture->item10Weight),
        ]);

        $this->packager->withHeaviestFirst()->pack();

        $resultedBins = $this->packager->getBins();

        $this->assertCount(9, $resultedBins[$this->binFixture->bin2Id]->getFittedItems());
        $this->assertCount(0, $resultedBins[$this->binFixture->bin1Id]->getFittedItems());
        $this->assertCount(1, $resultedBins[$this->binFixture->bin2Id]->getUnfittedItems());
        $this->assertCount(1, $resultedBins[$this->binFixture->bin1Id]->getUnfittedItems());
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
