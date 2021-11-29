<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Latuconsinafr\BinPackager\BinPackager3D\Bin;
use Latuconsinafr\BinPackager\BinPackager3D\Item;
use Latuconsinafr\BinPackager\BinPackager3D\Packager;
use Latuconsinafr\BinPackager\BinPackager3D\Types\SortType;

// Initialize the packager
$packager = new Packager(2, SortType::DESCENDING);

// Initialize all the bins
$packager->addBins([
    new Bin(1, 4, 4, 5, 50),
    new Bin(2, 8, 8, 5, 100)
]);

// Initialize all the items
$packager->addItems([
    new Item(1, 1, 4.225, 2, 5),
    new Item(2, 2, 5, 2, 2.525),
    new Item(3, 1, 3.5, 3, 10),
    new Item(4, 3.551, 2, 2, 12.5),
    new Item(5, 2.221, 1, 2, 6),
    new Item(6, 2.6, 4, 1, 7.5),
    new Item(7, 2, 5, 3, 4.3276),
    new Item(8, 3, 3.21, 3, 20),
    new Item(9, 4, 2, 3.5, 5),
    new Item(10, 2, 5.2, 2, 5),
    new Item(11, 3.1, 4, 5, 2),
    new Item(12, 2, 5, 2, 10),
    new Item(13, 3.5512, 2, 3, 5),
    new Item(14, 3, 2.12, 5, 8),
    new Item(15, 2, 1.55, 2, 3)
]);

// Start microtime to measure execution times
$start = microtime(true);

// Call the pack method to pack all the items inside the packager into the bin
while ($packager->withFirstFit()->pack()) {
}

$time_elapsed_secs = microtime(true) - $start;

// Dump time elapsed and the resulted bins
print_r("Time Elapsed: " . $time_elapsed_secs . " second(s)\n");
print_r("Result: \n");
print_r("Lower Bounds: " . $packager->getLowerBounds() . "\n");
print_r($packager->getBins());
print_r(json_encode($packager->getBins()));
