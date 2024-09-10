<?php

namespace App\Repositories\Product\Dto;

readonly class RawProductDto
{
    public function __construct(protected array $rawProductData) {}

    public function getCodeField(): ?string
    {
        return $this->getProductField('product_code');
    }

    public function getNameField(): ?string
    {
        return $this->getProductField('product_name');
    }

    public function getDescriptionField(): ?string
    {
        return $this->getProductField('product_description');
    }

    public function getStockField(): ?string
    {
        return $this->getProductField('stock');
    }

    public function getGbpPriceField(): ?string
    {
        return $this->getProductField('cost_in_g_b_p');
    }

    public function getDiscountedField(): ?string
    {
        return $this->getProductField('discontinued');
    }

    protected function getProductField(string $key, mixed $default = null): ?string
    {
        return $this->rawProductData[$key] ?? $default;
    }
}
