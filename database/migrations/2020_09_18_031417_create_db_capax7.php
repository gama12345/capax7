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
            $table->string('razon_social', 150);
            $table->string('rfc', 13);
            $table->string('direccion', 150);
            $table->string('telefono', 20);
            $table->string('presidente', 120);
            $table->string('pagina_web', 150);
            $table->string('facebook', 100);
            $table->string('instagram', 100);
            $table->string('twitter', 100);
            $table->string('logo', 100);

        });
        Schema::create('clients', function (Blueprint $table) { 
            $table->increments('id');
            $table->string('razon_social');
            $table->string('rfc', 13);
            $table->string('ciudad');
            $table->string('estado');
            $table->string('email');
            $table->string('contraseña')->default("capax7");
            $table->string('telefono', 20);
            $table->string('celular')->nullable();
            $table->string('pagina_web')->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('tipo_persona', 6);
            $table->string('es_lucrativa', 2)->nullable();
            $table->string('r_legal');
            $table->string('banco');
            $table->string('cta_bancaria');
            $table->string('clave_interbancaria');
            $table->string('imss')->nullable();
            $table->string('ace_stps')->nullable();
            $table->string('registrado_en', 4);
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
            $table->string('celular')->nullable();
            $table->string('domicilio');
            $table->integer('registrado_por')->unsigned();             
            $table->foreign('registrado_por')->references('id')->on('clients')->onDelete('cascade'); 
        });
        Schema::create('donations', function (Blueprint $table){
            $table->increments('id');
            $table->float('cantidad', 8, 2);	
            $table->date('fecha');	
            $table->integer('donante')->unsigned();
            $table->foreign('donante')->references('id')->on('donors')->onDelete('cascade');  
            $table->integer('cliente')->unsigned();             
            $table->foreign('cliente')->references('id')->on('clients')->onDelete('cascade'); 
        });
        Schema::create('revenues', function (Blueprint $table){
            $table->increments('id');
            $table->string('concepto', 100);
            $table->float('cantidad', 8, 2);	
            $table->date('fecha');	
            $table->integer('cliente')->unsigned();             
            $table->foreign('cliente')->references('id')->on('clients')->onDelete('cascade'); 
        });
        Schema::create('expenses', function (Blueprint $table){
            $table->increments('id');
            $table->string('concepto', 100);
            $table->float('cantidad', 8, 2);	
            $table->date('fecha');	
            $table->integer('cliente')->unsigned();             
            $table->foreign('cliente')->references('id')->on('clients')->onDelete('cascade'); 
        });
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
        });
    }
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
