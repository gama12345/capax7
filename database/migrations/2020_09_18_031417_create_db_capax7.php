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
            $table->string('rfc', 13);
            $table->primary('rfc');
            $table->string('email',100);
            $table->string('contraseña', 24);
            $table->string('razon_social', 150);
            $table->string('presidente', 100);
            $table->string('director_ejecutivo', 100);
            $table->string('logo', 100);
            $table->string('telefono', 10);
        });
        Schema::create('clients', function (Blueprint $table) { 
            $table->string('rfc', 13);
            $table->primary('rfc');
            $table->string('razon_social', 150);
            $table->string('email', 100);
            $table->string('contraseña', 24)->default("capax7");
            $table->string('telefono', 10);
            $table->string('celular', 10)->nullable();
            $table->string('tipo_persona', 6);
            $table->string('es_lucrativa', 2)->nullable();
            $table->string('r_legal', 150);
            $table->string('cta_bancaria', 30);
            $table->string('imss', 11);
            $table->string('ace_stps', 150);
        });

        Schema::create('documents', function (Blueprint $table){
            $table->increments('id');   
            $table->string('tipo', 50);
            $table->string('nombre', 50);    
            $table->string('rfc', 13);             
            $table->foreign('rfc')->references('rfc')->on('clients')->onDelete('cascade');       
        });

        Schema::create('donors', function (Blueprint $table){ 
            $table->string('rfc', 13);
            $table->primary('rfc');
            $table->string('razon_social', 150);
            $table->string('tipo_persona', 6);
            $table->string('nacionalidad', 30);
            $table->string('email', 100);
            $table->string('telefono', 10);
            $table->string('celular', 10)->nullable();
            $table->string('cliente', 13);             
            $table->foreign('cliente')->references('rfc')->on('clients')->onDelete('cascade'); 
        });

        Schema::create('donations', function (Blueprint $table){
            $table->increments('id');
            $table->float('cantidad', 10, 2);    
            $table->date('fecha');  
            $table->string('donante', 13);
            $table->foreign('donante')->references('rfc')->on('donors')->onDelete('cascade');  
            $table->string('cliente', 13);             
            $table->foreign('cliente')->references('rfc')->on('clients')->onDelete('cascade');  
        });


        Schema::create('social_networks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pagina_web', 150);
            $table->string('facebook', 100);
            $table->string('instagram', 100);
            $table->string('twitter', 100);
            $table->string('rfc', 13); 
            $table->foreign('rfc')->references('rfc')->on('clients')->onDelete('cascade');  
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street', 150);
            $table->string('number', 100);
            $table->string('neighborhood', 100);
            $table->string('postal_code', 100);
            $table->string('rfc', 13); 
            $table->foreign('rfc')->references('rfc')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('social_networks');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('donors');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('password_resets');
    }
}
