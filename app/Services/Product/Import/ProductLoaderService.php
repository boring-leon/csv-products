<?php

namespace App\Services\Product\Import;

use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Dto\RawProductDto;
use App\Services\Product\Enum\ProductHeaderEnum;
use App\Services\Product\Import\Exception\InvalidCsvHeaderException;
use Illuminate\Contracts\Filesystem\Filesystem;
use InvalidArgumentException;
use League\Csv\Reader;

/**
 * Since generic type declarations like Collection<Type> are not supported in PHP (sadly),
 * I use doc blocks to at least hint what is expected to be inside the collection.
 */
class ProductLoaderService
{
    public function __construct(
        protected Filesystem $filesystem
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

        if (!$this->validateFileHeader($reader)) {
            throw new InvalidCsvHeaderException('Provided file has invalid headers.');
        }

        $collection = new ProductCollection($reader->getRecords());

        if (!$this->validateCollectionDoesntHaveDuplicateProducts($collection)) {
            throw new InvalidArgumentException('Provided file contains duplicate rows. (by product code field)');
        }

        return $collection->mapInto(RawProductDto::class);
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
        return $collection->duplicates(ProductHeaderEnum::Code->value)->filter()->isEmpty();
    }

    protected function validateFileHeader(Reader $reader): bool
    {
        return empty(array_diff($reader->getHeader(), ProductHeaderEnum::headers()));
    }
}
