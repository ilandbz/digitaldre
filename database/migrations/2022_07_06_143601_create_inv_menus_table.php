<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->references('id')->on('inv_modulos')->onDelete('restrict');
            $table->string('x_nombre', 50);
            $table->string('x_favicon', 50);
            $table->string('x_route', 100);
            $table->string('x_url', 100);
            $table->tinyInteger('n_orden');
            $table->char('l_submenus', 1)->default('N')->comment("S=si, N=no");
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_menus');
    }
}
