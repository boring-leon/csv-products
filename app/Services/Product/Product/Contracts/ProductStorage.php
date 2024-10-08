<?php

namespace App\Services\Product\Product\Contracts;

use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Dto\ProductDto;

interface ProductStorage
{
    /**
     * @param \App\Services\Product\Collection\ProductCollection<ProductDto> $collection
     * @return void
     */
    public function insertMany(ProductCollection $collection): void;

    public function getExistingProductCodes(): array;
}
