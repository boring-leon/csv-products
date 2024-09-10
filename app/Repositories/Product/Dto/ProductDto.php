<?php

namespace App\Repositories\Product\Dto;

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
}
