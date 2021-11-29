<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D\Tests\TestCases\Handlers;

use Latuconsinafr\BinPackager\BinPackager3D\Handlers\IntersectionHandler;
use Latuconsinafr\BinPackager\BinPackager3D\Item;
use Latuconsinafr\BinPackager\BinPackager3D\Tests\Fixtures\ItemFixture;
use Latuconsinafr\BinPackager\BinPackager3D\Types\AxisType;
use Latuconsinafr\BinPackager\BinPackager3D\Types\PositionType;
use PHPUnit\Framework\TestCase;

/**
 * IntersectionHandler Test Case.
 */
class IntersectionHandlerTest extends TestCase
{
    /**
     * @var IntersectionHandler The intersection handler class.
     */
    private IntersectionHandler $intersectionHandler;

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
        $this->intersectionHandler = new IntersectionHandler();
        $this->itemFixture = new ItemFixture();
    }

    /**
     * isIntersected() method tested with two items which
     * has the same position.
     * 
     * @return void
     */
    public function testIsIntersected_Equal_Position(): void
    {
        $item1 = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );
        $item1->setPosition(PositionType::START_POSITION);

        $item2 = new Item(
            $this->itemFixture->item2Id,
            $this->itemFixture->item2Length,
            $this->itemFixture->item2Height,
            $this->itemFixture->item2Breadth,
            $this->itemFixture->item2Weight,
        );
        $item2->setPosition((PositionType::START_POSITION));

        $result = $this->intersectionHandler->isIntersected($item1, $item2);

        $this->assertTrue($result);
    }

    /**
     * isIntersected() method tested with two items which
     * has the different position but still intersected with each other.
     * In this case the item 2 position is still inside the item 1 area.
     * 
     * @return void
     */
    public function testIsIntersected_Different_Position_But_Still_Intersected(): void
    {
        $item1 = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );
        $item1->setPosition(PositionType::START_POSITION);

        $item2 = new Item(
            $this->itemFixture->item2Id,
            $this->itemFixture->item2Length,
            $this->itemFixture->item2Height,
            $this->itemFixture->item2Breadth,
            $this->itemFixture->item2Weight,
        );
        $item2->setPosition([
            AxisType::LENGTH => $item1->getLength() - 0.01,
            AxisType::HEIGHT => $item1->getHeight() - 0.01,
            AxisType::BREADTH => $item1->getBreadth() - 0.01
        ]);

        $result = $this->intersectionHandler->isIntersected($item1, $item2);

        $this->assertTrue($result);
    }

    /**
     * isIntersected() method tested with two items which
     * has the different position ant not intersected with each other.
     * In this case the item 2 position is right next to the item 1.
     * 
     * @return void
     */
    public function testIsIntersected_Different_Position_And_Not_Intersected(): void
    {
        $item1 = new Item(
            $this->itemFixture->item1Id,
            $this->itemFixture->item1Length,
            $this->itemFixture->item1Height,
            $this->itemFixture->item1Breadth,
            $this->itemFixture->item1Weight,
        );
        $item1->setPosition(PositionType::START_POSITION);

        $item2 = new Item(
            $this->itemFixture->item2Id,
            $this->itemFixture->item2Length,
            $this->itemFixture->item2Height,
            $this->itemFixture->item2Breadth,
            $this->itemFixture->item2Weight,
        );
        $item2->setPosition([
            AxisType::LENGTH => $item1->getLength(),
            AxisType::HEIGHT => $item1->getHeight(),
            AxisType::BREADTH => $item1->getBreadth()
        ]);

        $result = $this->intersectionHandler->isIntersected($item1, $item2);

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
