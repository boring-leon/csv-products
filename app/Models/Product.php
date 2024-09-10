<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'intProductDataId';

    protected $table = 'tblProductData';

    public $timestamps = false;

    protected $attributes = [
        'strProductName' => null,
        'strProductDesc' => null,
        'strProductCode' => null,
        'decPrice' => null,
        'intStock' => null,
        'dtmAdded' => null,
        'dtmDiscontinued' => null,
        'stmTimestamp' => null
    ];

    protected function casts(): array
    {
        return [
            'stmTimestamp' => 'datetime',
            'dtmDiscounted' => 'datetime',
            'dtmAdded' => 'datetime'
        ];
    }
}
