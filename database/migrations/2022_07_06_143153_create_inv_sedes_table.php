<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

class CreateInvSedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_sedes', function (Blueprint $table) {
            $table->id();
            $table->string('c_codigo', 10);
            $table->string('x_nombre');
            $table->string('x_direccion')->nullable();
            $table->string('x_latitud', 50)->nullable();
            $table->string('x_longitud', 50)->nullable();
            $table->string('x_departamento', 50)->nullable();
            $table->string('x_provincia', 50)->nullable();
            $table->string('x_distrito', 50)->nullable();
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->timestamps();
        });

        DB::table('inv_sedes')->insert([
            ['c_codigo' => '3201', 'x_nombre' => 'SIMON BOLIVAR (EX-INGENIEROS)'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_sedes');
    }
}
