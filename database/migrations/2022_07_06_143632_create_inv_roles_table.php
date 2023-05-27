<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInvRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_roles', function (Blueprint $table) {
            $table->id();
            $table->string('x_nombre', 50);
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->timestamps();
        });

        DB::table('inv_roles')->insert([
            ['x_nombre' => 'ADMINISTRADOR'],
            ['x_nombre' => 'OPERADOR'],
            ['x_nombre' => 'CONSULTA'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_roles');
    }
}
