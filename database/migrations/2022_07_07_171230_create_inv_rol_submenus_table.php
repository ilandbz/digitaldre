<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInvRolSubmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_rol_submenus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id')->references('id')->on('inv_roles')->onDelete('restrict');
            $table->foreignId('menu_id')->references('id')->on('inv_menus')->onDelete('restrict');
            $table->foreignId('submenu_id')->references('id')->on('inv_submenus')->onDelete('restrict');
            $table->char('l_crear', 1)->default('N')->comment("S=si, N=no");
            $table->char('l_editar', 1)->default('N')->comment("S=si, N=no");
            $table->char('l_eliminar', 1)->default('N')->comment("S=si, N=no");
            $table->char('l_otros', 1)->default('N')->comment("S=si, N=no");
            $table->char('l_activo', 1)->default('N')->comment("S=si, N=no");
            $table->timestamps();
        });

        DB::table('inv_rol_submenus')->insert([
            ['rol_id' => 1, 'menu_id' => 1, 'submenu_id' => 1, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 1, 'submenu_id' => 2, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 1, 'submenu_id' => 3, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 2, 'submenu_id' => 4, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 3, 'submenu_id' => 5, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 4, 'submenu_id' => 6, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 5, 'submenu_id' => 7, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 6, 'submenu_id' => 8, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
            ['rol_id' => 1, 'menu_id' => 7, 'submenu_id' => 9, 'l_crear' => 'S', 'l_editar' => 'S', 'l_eliminar' => 'S', 'l_otros' => 'N', 'l_activo' => 'S'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_rol_submenus');
    }
}
