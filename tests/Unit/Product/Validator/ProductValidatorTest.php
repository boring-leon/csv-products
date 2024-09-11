<?php

namespace Tests\Unit\Product\Validator;

use App\Services\Product\Dto\RawProductDto;
use App\Services\Product\Enum\ProductHeaderEnum;
use App\Services\Product\Import\Validator\ProductValidatorService;
use Tests\TestCase;

/**
 * Here I only test stock validator, as it is the only non-trivial one.
 * I think there is no point in testing strlen() checks, just to achieve coverage.
 */
class ProductValidatorTest extends TestCase
{
    protected ProductValidatorService $productValidatorService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productValidatorService = $this->app->make(ProductValidatorService::class);
    }

    public function testWhenStockCastsToStringItFails(): void
    {
        $product = new RawProductDto([
            ProductHeaderEnum::Stock->value => 'John Doe'
        ]);

        $this->assertFalse(
            $this->productValidatorService->isStockValid($product)
        );
    }

    public function testWhenStockCastsFloatItFails(): void
    {
        $product = new RawProductDto([
            ProductHeaderEnum::Stock->value => '4.5'
        ]);

        $this->assertFalse(
            $this->productValidatorService->isStockValid($product)
        );
    }

    public function testWhenStockCastsZeroItPasses(): void
    {
        $product = new RawProductDto([
            ProductHeaderEnum::Stock->value => '0'
        ]);

        $this->assertTrue(
            $this->productValidatorService->isStockValid($product)
        );
    }

    public function testWhenStockCastsToIntItPasses(): void
    {
        $product = new RawProductDto([
            ProductHeaderEnum::Stock->value => '5'
        ]);

        $this->assertTrue(
            $this->productValidatorService->isStockValid($product)
        );
    }
}
