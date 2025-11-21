<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtiUtilisateursTable extends Migration
{
    public function up()
    {
        Schema::create('UTI_Utilisateur', function (Blueprint $table) {
            $table->bigIncrements('UTI_ID');

            $table->string('UTI_Nom');
            $table->string('UTI_Email')->unique();
            $table->string('UTI_Password');
            $table->enum('UTI_Role', ['employe', 'admin'])->default('employe');
            $table->boolean('UTI_Actif')->default(true);

            $table->string('UTI_Login_Token')->nullable();

            $table->timestamp('UTI_Cree_Dte')->nullable();
            $table->unsignedBigInteger('UTI_Cree_UID')->nullable();
            $table->timestamp('UTI_Modif_Dte')->nullable();
            $table->unsignedBigInteger('UTI_Modif_UID')->nullable();
            $table->timestamp('UTI_Suppr_Dte')->nullable();
            $table->unsignedBigInteger('UTI_Suppr_UID')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('UTI_Utilisateur');
    }
}

