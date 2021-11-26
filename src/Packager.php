<?php

declare(strict_types=1);

namespace Latuconsinafr\BinPackager\BinPackager3D;

class Packager
{
    public array $bins = [];
    public array $items = [];

    public function __construct()
    {
    }

    public function addBin(float $length, float $breadth, float $height, float $weight): void
    {
        array_push(
            $this->bins,
            [$length, $breadth, $height, $weight]
        );
    }

    public function addItem(float $length, float $breadth, float $height, float $weight): void
    {
        array_push(
            $this->items,
            [$length, $breadth, $height, $weight]
        );
    }
}
