<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInvSubmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_submenus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->references('id')->on('inv_menus')->onDelete('restrict');
            $table->string('x_nombre', 50);
            $table->string('x_route', 100);
            $table->string('x_url', 100);
            $table->tinyInteger('n_orden');
            $table->char('l_opciones', 1)->default('N')->comment("S=si, N=no");
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no, D=defaul no se muestra");
        });
        
        DB::table('inv_modulos')->insert([
            ['x_nombre' => ' '],
            ['x_nombre' => 'INVENTARIO'],
            ['x_nombre' => 'ALMACEN'],
            ['x_nombre' => 'ADMINISTRADOR'],
        ]);

        DB::table('inv_menus')->insert([
            ['modulo_id' => 2, 'x_nombre' => 'Tablas', 'x_favicon' => 'fas fa-table', 'x_route' => 'tablas', 'x_url' => 'tablas', 'n_orden' => 0, 'l_submenus' => 'S'],
            ['modulo_id' => 4, 'x_nombre' => 'Sedes', 'x_favicon' => 'fas fa-map-marked-alt', 'x_route' => 'sedes', 'x_url' => 'sedes', 'n_orden' => 0, 'l_submenus' => 'N'],
            ['modulo_id' => 4, 'x_nombre' => 'Roles', 'x_favicon' => 'far fa-address-card', 'x_route' => 'roles', 'x_url' => 'roles', 'n_orden' => 0, 'l_submenus' => 'N'],
            ['modulo_id' => 4, 'x_nombre' => 'Usuarios', 'x_favicon' => 'fas fa-users', 'x_route' => 'usuarios', 'x_url' => 'usuarios', 'n_orden' => 1, 'l_submenus' => 'N'],
            ['modulo_id' => 4, 'x_nombre' => 'Trabajadores', 'x_favicon' => 'fas fa-user-tie', 'x_route' => 'trabajadores', 'x_url' => 'trabajadores', 'n_orden' => 1, 'l_submenus' => 'N'],
            ['modulo_id' => 4, 'x_nombre' => 'Configuración', 'x_favicon' => 'fas fa-cog', 'x_route' => 'config', 'x_url' => 'config', 'n_orden' => 2, 'l_submenus' => 'N'],
            ['modulo_id' => 4, 'x_nombre' => 'Logs', 'x_favicon' => 'fas fa-exclamation-triangle', 'x_route' => 'logs', 'x_url' => 'logs', 'n_orden' => 3, 'l_submenus' => 'N'],
        ]);
        
        DB::table('inv_submenus')->insert([
            ['menu_id' => 1, 'x_nombre' => 'Grupos', 'x_route' => 'tablas.grupos', 'x_url' => 'tablas/grupos', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 1, 'x_nombre' => 'Clases', 'x_route' => 'tablas.clases', 'x_url' => 'tablas/clases', 'n_orden' => 1, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 1, 'x_nombre' => 'Bienes', 'x_route' => 'tablas.bienes', 'x_url' => 'tablas/bienes', 'n_orden' => 2, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 2, 'x_nombre' => 'Sedes', 'x_route' => 'sedes', 'x_url' => 'sedes', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 3, 'x_nombre' => 'Roles', 'x_route' => 'roles', 'x_url' => 'roles', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 4, 'x_nombre' => 'Usuarios', 'x_route' => 'usuarios', 'x_url' => 'usuarios', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 5, 'x_nombre' => 'Trabajadores', 'x_route' => 'trabajadores', 'x_url' => 'trabajadores', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 6, 'x_nombre' => 'Configuración', 'x_route' => 'config', 'x_url' => 'config', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
            ['menu_id' => 7, 'x_nombre' => 'Logs', 'x_route' => 'logs', 'x_url' => 'logs', 'n_orden' => 0, 'l_opciones' => 'S', 'l_activo' => 'S'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_submenus');
    }
}
