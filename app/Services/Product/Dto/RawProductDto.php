<?php

namespace App\Services\Product\Dto;

use App\Services\Product\Enum\ProductHeaderEnum;

readonly class RawProductDto
{
    public function __construct(protected array $rawProductData) {}

    public function getCodeField(): ?string
    {
        return $this->getProductField(ProductHeaderEnum::Code);
    }

    public function getNameField(): ?string
    {
        return $this->getProductField(ProductHeaderEnum::Name);
    }

    public function getDescriptionField(): ?string
    {
        return $this->getProductField(ProductHeaderEnum::Description);
    }

    public function getStockField(): ?string
    {
        return $this->getProductField(ProductHeaderEnum::Stock);
    }

    public function getCostField(): ?string
    {
        return $this->getProductField(ProductHeaderEnum::Cost);
    }

    public function getDiscontinuedField(): ?string
    {
        return $this->getProductField(ProductHeaderEnum::Discontinued);
    }

    protected function getProductField(ProductHeaderEnum $header, mixed $default = null): ?string
    {
        return $this->rawProductData[$header->value] ?? $default;
    }
}
