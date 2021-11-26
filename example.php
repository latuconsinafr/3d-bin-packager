<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Latuconsinafr\BinPackager\BinPackager3D\Packager;

$packager = new Packager();
$packager->addBin(10.5, 5.25, 3.4, 20.2);
$packager->addBin(10.5, 5.25, 3.4, 20.2);

$packager->addItem(2.5, 5.5, 7.5, 10);
$packager->addItem(1.5, 2.5, 4.3, 5.5);

$packager->pack();

print_r($packager->bins);
