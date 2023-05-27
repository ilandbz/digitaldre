<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateInvUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->references('id')->on('inv_sedes')->onDelete('restrict');
            $table->foreignId('dependencia_id')->references('id')->on('inv_dependencias')->onDelete('restrict');
            $table->foreignId('rol_id')->references('id')->on('inv_roles')->onDelete('restrict');
            $table->string('x_nombres');
            $table->string('c_dni', 8)->nullable();
            $table->string('x_email', 50)->nullable();
            $table->string('x_telefono', 15)->nullable();
            $table->char('l_activo', 1)->default('S');
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->timestamps();
        });

        DB::table('inv_users')->insert([
            ['sede_id' => 1, 'dependencia_id' => 1, 'rol_id' => 1, 'x_nombres' => 'ADMINISTRADOR DEL SISTEMA', 'c_dni' => '12345678', 'x_email' => 'admin@gmail.com', 'username' => 'admin', 'password' => Hash::make('123456')]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_users');
    }
}
