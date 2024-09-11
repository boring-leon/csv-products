<?php

namespace App\Services\Product\Import\Validator;

use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Dto\RawProductDto;
use App\Services\Product\Enum\ProductHeaderEnum;
use Illuminate\Support\Collection;

class ProductCollectionValidatorService
{
    protected array $messages = [];

    public function __construct(
        protected ProductValidatorService $productValidatorService
    ) {}

    /**
     * @param \App\Services\Product\Collection\ProductCollection<RawProductDto> $collection
     * @return \App\Services\Product\Collection\ProductCollection<RawProductDto>
     */
    public function validateRawProductsCollection(ProductCollection $collection): ProductCollection
    {
        $this->resetErrors();

        return $collection->filter(fn(RawProductDto $rawProduct) => $this->validateRawProduct($rawProduct));
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

    public function getInvalidProductsCount(): int
    {
        return count($this->messages);
    }

    protected function validateRawProduct(RawProductDto $rawProduct): bool
    {
        $valid = true;

        if (!$this->productValidatorService->isCodeValid($rawProduct)) {
            $this->pushError($rawProduct, ProductHeaderEnum::Code);

            $valid = false;
        }

        if (!$this->productValidatorService->isNameValid($rawProduct)) {
            $this->pushError($rawProduct, ProductHeaderEnum::Name);

            $valid = false;
        }

        if (!$this->productValidatorService->isDescriptionValid($rawProduct)) {
            $this->pushError($rawProduct, ProductHeaderEnum::Description);

            $valid = false;
        }

        if (!$this->productValidatorService->isStockValid($rawProduct)) {
            $this->pushError($rawProduct, ProductHeaderEnum::Stock);

            $valid = false;
        }

        if (!$this->productValidatorService->isCostValid($rawProduct)) {
            $this->pushError($rawProduct, ProductHeaderEnum::Cost);

            $valid = false;
        }

        if (!$this->productValidatorService->isDiscontinuedStatusValid($rawProduct)) {
            $this->pushError($rawProduct, ProductHeaderEnum::Discontinued);

            $valid = false;
        }

        return $valid;
    }

    protected function resetErrors(): void
    {
        $this->messages = [];
    }

    protected function pushError(RawProductDto $rawProduct, ProductHeaderEnum $invalidField): void
    {
        $this->messages[$rawProduct->getCodeField()][] = $invalidField->value;
    }
}
