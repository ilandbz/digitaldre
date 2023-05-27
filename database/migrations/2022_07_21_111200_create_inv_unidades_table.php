<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvUnidadesTable extends Migration
{
   
    public function up()
    {
        Schema::create('inv_unidades', function (Blueprint $table) {
            $table->id();
            $table->string('x_unidades', 150);
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->foreignId('user_id')->references('id')->on('inv_users')->onDelete('restrict');
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('inv_unidades');
    }
}
