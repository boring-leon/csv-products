<?php

return [
    'disk' => env('PRODUCT_IMPORT_DISK', 'local'),

    /**
     * Here mentioned header fields enum could be used as well
     */
    'file_headers' => [
        'Product Code',
        'Product Name',
        'Product Description',
        'Stock',
        'Cost in GBP',
        'Discontinued'
    ]
];
