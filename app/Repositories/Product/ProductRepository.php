<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Repositories\Product\Dto\ProductDto;
use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Product\Contracts\ProductStorage;
use Illuminate\Database\Connection;
use Throwable;

/**
 * Chunk insert is done for performance reasons.
 * Note - in the future, we should specify row limit for a single csv.
 * Chunking is a good first step in making import process more scalable, but it is not enough.
 */
class ProductRepository implements ProductStorage
{
    public function __construct(protected Connection $db) {}

    /**
     * @inheritDoc
     */
    public function insertMany(ProductCollection $collection): void
    {
        $this->db->beginTransaction();

        try {
            $collection
                ->chunk(100)
                ->each(function (ProductCollection $products)
                {
                    $entries = $products->map(fn(ProductDto $product) => $product->toArray());

                    Product::insert($entries->toArray());
                });

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();

            throw $e;
        }
    }

    public function getExistingProductCodes(): array
    {
        return Product::pluck('strProductCode')->toArray();
    }
}
