<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWrkWorkSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('WRK_Work_Sessions', function (Blueprint $table) {
            $table->bigIncrements('WRK_ID');

            $table->unsignedBigInteger('WRK_UTI_ID');

            $table->timestamp('WRK_Dte_Heure_Deb')->nullable();
            $table->timestamp('WRK_Dte_Heure_Fin')->nullable();

            $table->integer('WRK_Duree_Minutes')->nullable()->comment('Durée arrondie en minutes');
            $table->enum('WRK_Type_Cloture', ['manuel','auto'])->nullable();
            $table->boolean('WRK_Est_Cloture_Auto')->default(false);

            $table->text('WRK_Note')->nullable();

            $table->timestamp('WRK_Cree_Dte')->nullable();
            $table->unsignedBigInteger('WRK_Cree_UID')->nullable();
            $table->timestamp('WRK_Modif_Dte')->nullable();
            $table->unsignedBigInteger('WRK_Modif_UID')->nullable();
            $table->timestamp('WRK_Suppr_Dte')->nullable();
            $table->unsignedBigInteger('WRK_Suppr_UID')->nullable();
            $table->timestamps();
            $table->foreign('WRK_UTI_ID')
                ->references('UTI_ID')
                ->on('UTI_Utilisateur')
                ->onDelete('cascade');

            // Index recommandés
            $table->index(['WRK_UTI_ID', 'WRK_Dte_Heure_Deb']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('WRK_Work_Sessions');
    }
}
