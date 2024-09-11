<?php

namespace App\Console\Commands\Product;

use App\Repositories\Product\MockProductRepository;
use App\Services\Product\Collection\ProductCollection;
use App\Services\Product\Import\ProductCollectionMapperService;
use App\Services\Product\Import\ProductLoaderService;
use App\Services\Product\Import\Validator\ProductCollectionValidatorService;
use App\Services\Product\Product\ProductService;
use App\Services\Product\Product\ResultDto;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ImportCsvProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        products:import
        { --path= : Path to a csv file }
        { --strict : Stops script execution and prints errors if any were detected while validating projects. }
        { --test : Will not actually insert processed records into the database. }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import product rows from csv to database.';

    public function __construct(
        protected ProductLoaderService              $loaderService,
        protected ProductCollectionValidatorService $validatorService,
        protected ProductCollectionMapperService    $mapperService,
        protected ProductService                    $productService
    )
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->isRunningInTestMode()) {
            $this->useMockProductStorage();
        }

        if (!$path = $this->getFilePath()) {
            $this->error('Please provide valid import file path.');

            return self::FAILURE;
        }

        if (!$rawProducts = $this->loadProducts($path)) {
            return self::FAILURE;
        }

        if (!$validatedProducts = $this->validateProducts($rawProducts)) {
            return self::FAILURE;
        }

        $result = $this->productService->persistProducts(
            products: $this->mapperService->mapRawInputToProductCollection($validatedProducts)
        );

        $this->renderOperationSummary(
            inputProductsCount: $rawProducts->count(),
            result: $result
        );

        return self::SUCCESS;
    }

    protected function validateProducts(ProductCollection $input): ProductCollection|false
    {
        $validatedProducts = $this->validatorService->validateRawProductsCollection($input);

        if (
            $this->validatorService->hasErrors()
            && $this->isRunningInStrictMode()
        ) {
            $this->renderProductValidationErrors($this->validatorService->messages());

            return false;
        }

        return $validatedProducts;
    }

    protected function loadProducts(string $path): ProductCollection|false
    {
        try {
            return $this->loaderService->loadRawProducts($path);
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return false;
        }
    }

    protected function getFilePath(): ?string
    {
        return trim($this->option('path'));
    }

    protected function isRunningInStrictMode(): bool
    {
        return $this->option('strict');
    }

    protected function isRunningInTestMode(): bool
    {
        return $this->option('test');
    }

    protected function useMockProductStorage(): void
    {
        $this->productService->setStorage(
            app(MockProductRepository::class)
        );
    }

    protected function renderOperationSummary(
        int       $inputProductsCount,
        ResultDto $result
    ): void
    {
        $this->info(sprintf(
            'Total products %d, inserted %d, invalid %d, skipped %d.',
            $inputProductsCount,
            $result->insertedProducts->count(),
            $this->validatorService->getInvalidProductsCount(),
            $result->skippedProducts->count()
        ));

        $skippedCodes = $result->skippedProducts->pluck('code')->implode(', ');

        if ($skippedCodes) {
            $this->info(sprintf(
                'Skipped codes: %s',
                $skippedCodes
            ));
        }
    }

    protected function renderProductValidationErrors(Collection $errors): void
    {
        $this->newLine();

        $errors->each(function (string $error)
        {
            $this->error($error);
            $this->newLine();
        });
    }
}
