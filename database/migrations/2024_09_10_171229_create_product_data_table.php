<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE TABLE tblProductData (
              intProductDataId int(10) unsigned NOT NULL AUTO_INCREMENT,
              strProductName varchar(50) NOT NULL,
              strProductDesc varchar(255) NOT NULL,
              strProductCode varchar(10) NOT NULL,
              dtmAdded datetime DEFAULT NULL,
              dtmDiscontinued datetime DEFAULT NULL,
              stmTimestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (intProductDataId),
              UNIQUE KEY (strProductCode)
            ) ENGINE=InnoDB
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblProductData');
    }
};
