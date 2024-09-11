<?php

namespace App\Services\Product\Enum;

enum ProductHeaderEnum: string
{
    case Code = 'Product Code';
    case Name = 'Product Name';
    case Description = 'Product Description';
    case Stock = 'Stock';
    case Cost = 'Cost in GBP';
    case Discontinued = 'Discontinued';

    public static function headers(): array
    {
        return array_map(
            fn(ProductHeaderEnum $instance) => $instance->value,
            self::cases()
        );
    }
}
