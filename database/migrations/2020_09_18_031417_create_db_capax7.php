<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbCapax7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table){
            $table->increments('id');
            $table->string('email');
            $table->string('contraseña');
        });
        Schema::create('clients', function (Blueprint $table) { 
            $table->increments('id');
            $table->string('razon_social');
            $table->string('tipo_persona', 6);
            $table->string('rfc', 13);
            $table->string('email');
            $table->string('contraseña')->default("capax7");
            $table->string('telefono', 20);
            $table->string('celular');
            $table->string('pagina_web');
            $table->string('facebook', 100);
            $table->string('twitter', 100);
            $table->string('instagram', 100);
        });

        Schema::create('documents', function (Blueprint $table){
            $table->increments('id');	
            $table->string('tipo');
            $table->string('nombre');    
            $table->integer('cliente')->unsigned();             
            $table->foreign('cliente')->references('id')->on('clients')->onDelete('cascade');       
        });

        Schema::create('donors', function (Blueprint $table){ 
            $table->increments('id');
            $table->string('razon_social');
            $table->string('tipo_persona', 6);
            $table->string('rfc', 13);
            $table->string('nacionalidad');
            $table->string('email');
            $table->string('telefono', 20);
            $table->string('domicilio');
            $table->float('aportacion_mensual', 8, 2);
            $table->string('celular');

        });

        Schema::create('donations', function (Blueprint $table){
            $table->increments('id');
            $table->float('cantidad', 8, 2);	
            $table->date('fecha');	
            $table->integer('donante')->unsigned();
            $table->foreign('donante')->references('id')->on('donors')->onDelete('cascade');   
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('donors');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('password_resets');
    }
}