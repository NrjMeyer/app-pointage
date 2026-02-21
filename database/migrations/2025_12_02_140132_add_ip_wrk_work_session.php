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
		Schema::table('WRK_Work_Sessions', function (Blueprint $table) {
			$table->string('WRK_IP_Debut', 45)->nullable()->after('WRK_Dte_Heure_Deb');
			$table->string('WRK_IP_Fin', 45)->nullable()->after('WRK_Dte_Heure_Fin');
		});
	}

	public function down(): void
	{
		Schema::table('WRK_Work_Sessions', function (Blueprint $table) {
			$table->dropColumn(['WRK_IP_Debut', 'WRK_IP_Fin']);
		});
	}
};
