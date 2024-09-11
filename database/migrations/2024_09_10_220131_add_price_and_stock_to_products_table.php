<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Column names according to convention from raw sql file: type + cammelCaseColumn
 * I personally used snake_case without type prefixing.
 * I assumed that provided sql establishes a convention that should be followed.
 *
 * Note - price field could be an integer, representing price in cents.
 * This would be a bit more complex, requiring code-level conversions (complexity + potential bugs)
 * However integers tend to be a bit faster and uses less storage space in the database.
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
            $table->decimal('decPrice');
            $table->smallInteger('intStock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblProductData', function (Blueprint $table)
        {
            $table->dropColumn('decPrice');
            $table->dropColumn('intStock');
        });
    }
};
