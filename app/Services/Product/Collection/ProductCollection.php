<?php

namespace App\Services\Product\Collection;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProductCollection extends Collection
{
    /**
     * This step attempts to minimise typo-related issues while accessing csv columns.
     * A more robust solution would be to define supported columns as PHP enum, and use it everywhere.
     * In general, I prefer to avoid assoc arrays and have everything typed.
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
