<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo_cliente')->nullable();
            $table->string('codigo_id', 12)->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cidade', 255)->nullable();
            $table->integer('responses')->nullable();
            $table->integer('id_listagem')->nullable();
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
        Schema::dropIfExists('endereco');
    }
}
