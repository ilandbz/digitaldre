<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInvBienesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_bienes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->references('id')->on('inv_grupos')->onDelete('restrict');
            $table->foreignId('clase_id')->references('id')->on('inv_clases')->onDelete('restrict');
            $table->string('c_codigo', 10);
            $table->string('x_nombre');
            $table->string('x_resolucion', 100);
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->foreignId('user_id')->references('id')->on('inv_users')->onDelete('restrict');
            $table->timestamps();
        });

        DB::table('inv_grupos')->insert([
            //['c_codigo' => '04', 'x_nombre' => 'AGRICOLA Y PESQUERO', 'user_id' => 1],
            //['c_codigo' => '11', 'x_nombre' => 'AIRE ACONDICIONADO Y REFRIGERACION', 'user_id' => 1],
            //['c_codigo' => '18', 'x_nombre' => 'ANIMALES', 'user_id' => 1],
            //['c_codigo' => '25', 'x_nombre' => 'ASEO Y LIMPIEZA', 'user_id' => 1],
            //['c_codigo' => '32', 'x_nombre' => 'COCINA Y COMEDOR', 'user_id' => 1],
            //['c_codigo' => '39', 'x_nombre' => 'CULTURA Y ARTE', 'user_id' => 1],
            //['c_codigo' => '46', 'x_nombre' => 'ELECTRICIDAD Y ELECTRONICA', 'user_id' => 1],
            //['c_codigo' => '53', 'x_nombre' => 'HOSPITALIZACION', 'user_id' => 1],
            //['c_codigo' => '60', 'x_nombre' => 'INSTRUMENTO DE MEDICION', 'user_id' => 1],
            ['c_codigo' => '67', 'x_nombre' => 'MAQUINARIA, VEHICULOS Y OTROS', 'user_id' => 1],
            ['c_codigo' => '74', 'x_nombre' => 'OFICINA', 'user_id' => 1],
            //['c_codigo' => '81', 'x_nombre' => 'RECREACION Y DEPORTE', 'user_id' => 1],
            //['c_codigo' => '88', 'x_nombre' => 'SEGURIDAD INDUSTRIAL', 'user_id' => 1],
            ['c_codigo' => '95', 'x_nombre' => 'TELECOMUNICACIONES', 'user_id' => 1]
        ]);
        
        DB::table('inv_clases')->insert([
            ['c_codigo' => '08', 'x_nombre' => 'COMPUTO', 'user_id' => 1],
            ['c_codigo' => '22', 'x_nombre' => 'EQUIPO', 'user_id' => 1],
            ['c_codigo' => '64', 'x_nombre' => 'MOBILIARIO', 'user_id' => 1],
            ['c_codigo' => '82', 'x_nombre' => 'VEHICULO', 'user_id' => 1]
        ]);
        
        /*DB::table('inv_bienes')->insert([
            ['grupo_id' => 1, 'clase_id' => 1, 'c_codigo' => '08', 'x_nombre' => 'COMPUTO', 'x_resolucion' => '', 'user_id' => 1],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_bienes');
    }
}
