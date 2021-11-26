<?php

/**
 * 3D Bin Packager
 *
 * @license   MIT
 * @author    Farista Latuconsina
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Latuconsinafr\BinPackager\BinPackager3D\Packager;

// Initialize the packager
$packager = new Packager();

// Initialize the bin
$packager->addBin(20.5, 12.25, 5.4, 20.2);

// Initialize the item
$packager->addItem(2.5, 5.5, 7.5, 10);
$packager->addItem(1.5, 2.5, 4.3, 5.5);

// Call the pack method to pack all the items inside the packager into the bin
$packager->pack();

// Dump the resulted bins
print_r($packager->getBins());
