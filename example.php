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


// Initialize the packager
$packager = new Packager();

// Initialize all the bins
$packager->addBins([
    new Bin(1, 4, 4, 5, 50),
    new Bin(2, 8, 8, 5, 100)
]);

// Initialize all the items
$packager->addItems([
    new Item(1, 1, 4, 2, 5),
    new Item(2, 2, 5, 2, 2.5),
    new Item(3, 1, 3, 3, 10),
    new Item(4, 3, 2, 2, 12.5),
    new Item(5, 2, 1, 2, 6),
    new Item(6, 2, 4, 1, 7.5),
    new Item(7, 2, 5, 3, 4.3),
    new Item(8, 3, 3, 3, 20),
    new Item(9, 4, 2, 3, 5),
    new Item(10, 2, 5, 2, 5),
    new Item(11, 3, 4, 5, 2),
    new Item(12, 2, 5, 2, 10),
    new Item(13, 3, 2, 3, 5),
    new Item(14, 3, 2, 5, 8),
    new Item(15, 2, 1, 2, 3)
]);

// Start microtime to measure execution times
$start = microtime(true);

// Call the pack method to pack all the items inside the packager into the bin
while ($packager->withFirstFitDecreasing()->pack()) {
}

$time_elapsed_secs = microtime(true) - $start;

// Dump time elapsed and the resulted bins
print_r("Time Elapsed: " . $time_elapsed_secs . " second(s)\n");
print_r("Result: \n");
print_r($packager->getBins());
