<?php

namespace App\Repositories\Product\Dto;

use Carbon\CarbonImmutable;

readonly class ProductDto
{
    public function __construct(
        public string $code,
        public string $name,
        public string $description,
        public int    $stock,
        public int    $gbpPrice,
        public bool   $isDiscounted
    ) {}

    public function toArray(): array
    {
        $now = CarbonImmutable::now();

        return [
            'strProductName' => $this->name,
            'strProductDesc' => $this->description,
            'strProductCode' => $this->code,
            'decPrice' => $this->gbpPrice,
            'intStock' => $this->stock,
            'dtmAdded' => $now,
            'dtmDiscontinued' => $this->isDiscounted ? $now : null
        ];
    }
}
