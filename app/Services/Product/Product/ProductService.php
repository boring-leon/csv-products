<?php

namespace App\Services\Product\Product;

use App\Repositories\Product\Dto\ProductDto;
use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Product\Contracts\ProductStorage;

class ProductService
{
    public function __construct(
        protected ProductStorage $storage
    ) {}

    /**
     * @param \App\Services\Product\Collection\ProductCollection<ProductDto> $products
     * @return \App\Services\Product\Product\ResultDto
     * @throws \Throwable
     */
    public function persistProducts(ProductCollection $products): ResultDto
    {
        $skippedProducts = $products->filter(fn(ProductDto $product) => !$this->shouldPersist($product));

        $productsToPersist = $products->whereNotIn('code', $skippedProducts->pluck('code'));

        $this->storage->insertMany($productsToPersist);

        return new ResultDto(
            insertedProducts: $productsToPersist,
            skippedProducts: $skippedProducts,
        );
    }

    protected function shouldPersist(ProductDto $product): bool
    {
        if ($product->stock < 10 && $product->gbpPrice < 5) {
            return false;
        }

        if ($product->gbpPrice > 1000) {
            return false;
        }

        return true;
    }

    public function setStorage(ProductStorage $storage): void
    {
        $this->storage = $storage;
    }
}
