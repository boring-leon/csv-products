<?php

namespace App\Services\Product\Validator;

use App\Repositories\Product\Dto\RawProductDto;
use App\Services\Product\Collection\ProductCollection;
use Illuminate\Support\Collection;

class ProductCollectionValidatorService
{
    protected array $messages = [];

    public function __construct(protected ProductValidatorService $productValidatorService) {}

    /**
     * @param \App\Services\Product\Collection\ProductCollection<RawProductDto> $collection
     * @return bool
     */
    public function validateRawProductsCollection(ProductCollection $collection): bool
    {
        $this->resetErrors();

        $collection->each(function (RawProductDto $rawProduct)
        {
            $this->validateRawProduct($rawProduct);
        });

        return !$this->hasErrors();
    }

    public function messages(): Collection
    {
        return collect($this->messages)->map(function (array $invalidFields, string $productCode)
        {
            return "Product {$productCode} has invalid fields: " . implode(',', $invalidFields);
        });
    }

    public function hasErrors(): bool
    {
        return !empty($this->messages);
    }

    protected function validateRawProduct(RawProductDto $rawProduct): bool
    {
        $valid = true;

        if (!$this->productValidatorService->isCodeValid($rawProduct)) {
            $this->pushError($rawProduct, 'product_code');

            $valid = false;
        }

        if (!$this->productValidatorService->isNameValid($rawProduct)) {
            $this->pushError($rawProduct, 'product_name');

            $valid = false;
        }

        if (!$this->productValidatorService->isDescriptionValid($rawProduct)) {
            $this->pushError($rawProduct, 'product_description');

            $valid = false;
        }

        if (!$this->productValidatorService->isStockValid($rawProduct)) {
            $this->pushError($rawProduct, 'stock');

            $valid = false;
        }

        if (!$this->productValidatorService->isPriceValid($rawProduct)) {
            $this->pushError($rawProduct, 'price');

            $valid = false;
        }

        if (!$this->productValidatorService->isDiscountValid($rawProduct)) {
            $this->pushError($rawProduct, 'discount');

            $valid = false;
        }

        return $valid;
    }

    protected function resetErrors(): void
    {
        $this->messages = [];
    }

    protected function pushError(RawProductDto $rawProduct, string $invalidField): void
    {
        $this->messages[$rawProduct->getCodeField()][] = $invalidField;
    }
}
