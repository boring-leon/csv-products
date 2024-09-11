<?php

namespace App\Services\Product\Import\Validator;

use App\Services\Product\Dto\RawProductDto;

/**
 * Another approach to a validator like this could be to use Laravel ValidatesAttributes trait and call its methods.
 * I think that traits tend to "blur" the code and such a simple filter is fine with regular PHP.
 */
class ProductValidatorService
{
    public function isCodeValid(RawProductDto $rawProduct): bool
    {
        return strlen($rawProduct->getCodeField()) <= 10;
    }

    public function isNameValid(RawProductDto $rawProduct): bool
    {
        return strlen($rawProduct->getCodeField()) <= 50;
    }

    public function isDescriptionValid(RawProductDto $rawProduct): bool
    {
        return strlen($rawProduct->getCodeField()) <= 255;
    }

    public function isStockValid(RawProductDto $rawProduct): bool
    {
        return filter_var($rawProduct->getStockField(), FILTER_VALIDATE_INT) !== false
            && (int)$rawProduct->getStockField() >= 0;
    }

    public function isCostValid(RawProductDto $rawProduct): bool
    {
        return floatval($rawProduct->getCostField()) > 0;
    }

    public function isDiscontinuedStatusValid(RawProductDto $rawProduct): bool
    {
        return in_array($rawProduct->getDiscontinuedField(), ['yes', 'no']);
    }
}
