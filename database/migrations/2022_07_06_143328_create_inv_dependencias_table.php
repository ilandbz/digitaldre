<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInvDependenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_dependencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->references('id')->on('inv_sedes')->onDelete('restrict');
            $table->string('c_codigo', 10);
            $table->string('x_nombre');
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->timestamps();
        });

        DB::table('inv_dependencias')->insert([
            ['sede_id' => 1, 'c_codigo' => '320101', 'x_nombre' => 'INFORMATICA'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_dependencias');
    }
}
