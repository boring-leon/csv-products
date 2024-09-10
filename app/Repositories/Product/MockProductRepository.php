<?php

namespace App\Repositories\Product;

use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Product\Contracts\ProductStorage;

class MockProductRepository implements ProductStorage
{
    public function insertMany(ProductCollection $collection): void {}
}
