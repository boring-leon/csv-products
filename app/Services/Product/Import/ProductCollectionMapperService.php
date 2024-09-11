<?php

namespace App\Services\Product\Import;

use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Dto\ProductDto;
use App\Services\Product\Dto\RawProductDto;

class ProductCollectionMapperService
{
    /**
     * @param \App\Services\Product\Collection\ProductCollection<RawProductDto> $rawProductCollection
     * @return \App\Services\Product\Collection\ProductCollection<ProductDto>
     */
    public function mapRawInputToProductCollection(ProductCollection $rawProductCollection): ProductCollection
    {
        return $rawProductCollection->map(function (RawProductDto $rawProduct)
        {
            return new ProductDto(
                code: $rawProduct->getCodeField(),
                name: $rawProduct->getNameField(),
                description: $rawProduct->getDescriptionField(),
                stock: (int)$rawProduct->getStockField(),
                price: (float)$rawProduct->getCostField(),
                isDiscounted: $rawProduct->getDiscountedField() === 'yes'
            );
        });
    }
}
