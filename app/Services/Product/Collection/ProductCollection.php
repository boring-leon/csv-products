<?php

namespace App\Services\Product\Collection;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProductCollection extends Collection
{
    /**
     * This step attempts to minimise typo-related issues with identifying csv columns
     * by transforming each header into snake_case_notation.
     */
    public function transformHeadersToSnakeCaseNotation(): static
    {
        return $this->map(function (array $row)
        {
            return Arr::mapWithKeys($row, function (mixed $field, string $fieldKey)
            {
                return [str($fieldKey)->snake()->toString() => $field];
            });
        });
    }
}
