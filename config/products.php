<?php

return [
    'disk' => env('PRODUCT_IMPORT_DISK', 'local'),

    'file_headers' => [
        'Product Code',
        'Product Name',
        'Product Description',
        'Stock',
        'Cost in GBP',
        'Discontinued'
    ]
];
