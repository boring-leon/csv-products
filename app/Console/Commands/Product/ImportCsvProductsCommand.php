<?php

namespace App\Console\Commands\Product;

use App\Services\Product\Importer\ProductImporterService;
use App\Services\Product\Validator\ProductCollectionValidatorService;
use App\Services\ProductMapperService;
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
        { --show-errors : Indicates that the command should print validation errors for each product }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(
        protected ProductImporterService            $importerService,
        protected ProductCollectionValidatorService $validatorService,
        protected ProductMapperService              $mapperService
    )
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$path = trim($this->option('path'))) {
            return self::FAILURE;
        }

        try {
            $input = $this->importerService->getRawProducts($path);
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }


        if (!$this->validatorService->validateRawProductsCollection($input)) {
            $this->renderProductValidationErrors($this->validatorService->messages());

            return self::FAILURE;
        }

        $products = $this->mapperService->mapRawInputToProductCollection($input);

        return self::SUCCESS;
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
