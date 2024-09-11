<?php

namespace App\Services\Product\Import;

use App\Repositories\Product\Dto\RawProductDto;
use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Import\Exception\InvalidCsvHeaderException;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use InvalidArgumentException;
use League\Csv\Reader;

class ProductLoaderService
{
    public function __construct(
        protected Filesystem $filesystem,
        protected Repository $config
    ) {}

    /**
     * @param string $filePath
     * @return \App\Services\Product\Collection\ProductCollection<RawProductDto>
     * @throws \App\Services\Product\Import\Exception\InvalidCsvHeaderException
     * @throws \League\Csv\Exception
     */
    public function loadRawProducts(string $filePath): ProductCollection
    {
        $csvFileContent = $this->getFileContent($filePath);

        $reader = Reader::createFromString($csvFileContent)->setHeaderOffset(0);

        if (!$this->validateFileHeaderAgainstConfig($reader)) {
            throw new InvalidCsvHeaderException('Provided file has invalid headers.');
        }

        $collection = new ProductCollection($reader->getRecords());

        if (!$this->validateCollectionDoesntHaveDuplicateProducts($collection)) {
            throw new InvalidArgumentException('Provided file contains duplicate rows. (by product code field)');
        }

        return $collection->transformHeadersToSnakeCaseNotation()
            ->mapInto(RawProductDto::class);
    }

    protected function getFileContent(string $path): string
    {
        if (!$this->filesystem->exists($path)) {
            throw new InvalidArgumentException('Provided file does not exist.');
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'csv') {
            throw new InvalidArgumentException('Provided file is not a csv.');
        }

        return $this->filesystem->get($path);
    }

    protected function validateCollectionDoesntHaveDuplicateProducts(ProductCollection $collection): bool
    {
        return $collection->duplicates('Product Code')->filter()->isEmpty();
    }

    protected function validateFileHeaderAgainstConfig(Reader $reader): bool
    {
        return $reader->getHeader() === $this->config->get('products.file_headers');
    }
}
