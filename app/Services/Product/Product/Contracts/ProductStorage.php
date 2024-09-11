<?php

namespace App\Services\Product\Product\Contracts;

use App\Repositories\Product\Dto\ProductDto;
use App\Services\Product\Collection\ProductCollection;

interface ProductStorage
{
    /**
     * @param \App\Services\Product\Collection\ProductCollection<ProductDto> $collection
     * @return void
     */
    public function insertMany(ProductCollection $collection): void;

    public function getExistingProductCodes(): array;
}
