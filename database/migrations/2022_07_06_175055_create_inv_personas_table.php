<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvPersonasTable extends Migration
{
 /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_personas', function (Blueprint $table) {
            $table->id();
            $table->string('c_dni', 10);
            $table->string('x_nombre', 100);
            $table->string('x_telefono', 9);
            $table->string('x_email', 45);
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
        Schema::dropIfExists('inv_personas');
    }
}
