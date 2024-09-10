<?php

namespace App\Services\Product\Import\Validator;

use App\Repositories\Product\Dto\RawProductDto;

class ProductValidatorService
{
    public function isCodeValid(RawProductDto $rawProduct): bool
    {
        return true;
    }

    public function isNameValid(RawProductDto $rawProduct): bool
    {
        return true;
    }

    public function isDescriptionValid(RawProductDto $rawProduct): bool
    {
        return true;
    }

    public function isStockValid(RawProductDto $rawProduct): bool
    {
        return filter_var($rawProduct->getStockField(), FILTER_VALIDATE_INT) !== false
            && (int)$rawProduct->getStockField() >= 0;
    }

    public function isPriceValid(RawProductDto $rawProduct): bool
    {
        return floatval($rawProduct->getGbpPriceField()) > 0;
    }

    public function isDiscountValid(RawProductDto $rawProduct): bool
    {
        return in_array($rawProduct->getDiscountedField(), ['yes', 'no']);
    }
}
