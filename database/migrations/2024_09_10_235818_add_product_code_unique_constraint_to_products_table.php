<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * I think it is only logical to add this constraint as product code looks like a unique supplier-generated ID.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tblProductData', function (Blueprint $table)
        {
            $table->string('strProductCode', 10)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblProductData', function (Blueprint $table)
        {
            $table->dropUnique(['strProductCode']);
        });
    }
};
