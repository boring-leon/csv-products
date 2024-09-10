<?php

namespace App\Services\Product\Product;

use App\Services\Product\Collection\ProductCollection;

readonly class ResultDto
{
    public function __construct(
        public ProductCollection $insertedProducts,
        public ProductCollection $skippedProducts
    ) {}
}
