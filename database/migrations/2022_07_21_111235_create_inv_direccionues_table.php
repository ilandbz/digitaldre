<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvDireccionuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_direccionues', function (Blueprint $table) {
            $table->id();
            $table->string('x_direcciones', 20);
            $table->char('l_activo', 1)->default('S')->comment("S=si, N=no");
            $table->foreignId('user_id')->references('id')->on('inv_users')->onDelete('restrict');
            $table->timestamps();
        });
    }
     //
     //php artisan migrate --path=/database/migrations/2022_10_24_102816_create_inv_direccionues_table.php
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_direccionues');
    }
}
