<?php

namespace App\Services\Product\Dto;

use Carbon\CarbonImmutable;

readonly class ProductDto
{
    public function __construct(
        public string $code,
        public string $name,
        public string $description,
        public int    $stock,
        public int    $price,
        public bool   $isDiscontinued
    ) {}

    /**
     * Using immutable datetime is not relevant in this very code,
     * however I think it's a good practice to use immutable as a default approach wherever possible.
     */
    public function toArray(): array
    {
        $now = CarbonImmutable::now();

        return [
            'strProductName' => $this->name,
            'strProductDesc' => $this->description,
            'strProductCode' => $this->code,
            'decPrice' => $this->price,
            'intStock' => $this->stock,
            'dtmAdded' => $now,
            'dtmDiscontinued' => $this->isDiscontinued ? $now : null
        ];
    }
}
