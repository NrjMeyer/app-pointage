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
        Schema::table('UTI_Utilisateur', function (Blueprint $table) {
            $table->boolean('UTI_IP_Restriction')->default(0)->after('UTI_Actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('UTI_Utilisateur', function (Blueprint $table) {
            $table->dropColumn('UTI_IP_Restriction');
        });
    }
};
