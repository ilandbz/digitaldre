<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvUgelesTable extends Migration
{
    /**php 
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_ugeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->references('id')->on('inv_personas')->onDelete('restrict');
            //$table->string('c_dni', 10)->nullable();
            //$table->string('x_nombres', 150);
            //$table->string('x_email', 45)->nullable();
            $table->string('x_email2', 45)->nullable();
            //$table->string('x_telefono', 20)->nullable();
            $table->string('x_telefono2', 20)->nullable();
            $table->foreignId('cargo_id')->references('id')->on('inv_cargos')->onDelete('restrict');
            $table->foreignId('unidad_id')->references('id')->on('inv_unidades')->onDelete('restrict');
            $table->foreignId('direccion_id')->references('id')->on('inv_direccionues')->onDelete('restrict');
            $table->foreignId('area_id')->references('id')->on('inv_areas')->onDelete('restrict');
            $table->foreignId('dependencia_id')->references('id')->on('inv_dependencias')->onDelete('restrict');
            $table->string('x_tipopersonal', 30)->nullable();
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->foreignId('user_id')->references('id')->on('inv_users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_ugeles');
    }
}
