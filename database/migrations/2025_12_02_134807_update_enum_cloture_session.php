<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE `WRK_Work_Sessions`
            MODIFY `WRK_Type_Cloture`
            ENUM('manuel', 'auto', 'admin')
            NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		DB::statement("
            ALTER TABLE `WRK_Work_Sessions`
            MODIFY `WRK_Type_Cloture`
            ENUM('manuel', 'auto')
            NULL
        ");
    }
};
