<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pausas_asesor')) {
            Schema::create('pausas_asesor', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('ASESOR_ase_id');
                $table->dateTime('hora_inicio');
                $table->dateTime('hora_fin')->nullable();
                $table->unsignedInteger('duracion')->nullable()->comment('Duracion en minutos');
                $table->timestamps();

                $table->foreign('ASESOR_ase_id')
                      ->references('ase_id')
                      ->on('asesor')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pausas_asesor');
    }
};
