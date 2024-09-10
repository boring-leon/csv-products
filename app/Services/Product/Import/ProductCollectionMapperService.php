<?php

namespace App\Services\Product\Import;

use App\Repositories\Product\Dto\ProductDto;
use App\Repositories\Product\Dto\RawProductDto;
use App\Services\Product\Collection\ProductCollection;

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
                gbpPrice: (float)$rawProduct->getGbpPriceField(),
                isDiscounted: $rawProduct->getDiscountedField() === 'yes'
            );
        });
    }
}
