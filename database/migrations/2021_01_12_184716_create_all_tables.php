<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('states',function(Blueprint $table){
            $table->id(); 
            $table->string('name'); 
        });
        
        Schema::create('cities', function(Blueprint $table){
            $table->id(); 
            $table->string('name');
            $table->unsignedBigInteger('state_id')->nullable(); 
            $table->foreign('state_id')->references('id')->one('states'); 

        });
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('cnpj')->unique->nullable();
            $table->string('inscricao')->unique()->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('site')->nullable();
            $table->string('address')->nullable(); 
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('bairro')->nullable(); 
            $table->unsignedBigItenger('state_id')->nullable();
            $table->string('phone')->nullable(); 
            $table->string('counter')->nullable();
            $table->string('crc')->nullable(); 
            $table->string('logo')->default('default.png');  
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states'); 
            $table->timestamps();
        });


        
        
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('phone')->nullable();
            $table->string('cpf')->unique(); 
            $table->unsignedSmallInteger('age')->nullable(); 
            $table->string('email')->unique()->nullable();; 
            $table->string('avatar')->default('default.png'); 
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('city_id')->references('id')->on('companies'); 
            $table->timestamps();
        });


        Schema::create('unittypes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('created_by')->references('id') ->on('users'); 
            $table->timestamps();
        });


        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('details'); 
            $table->number('area')->nullable();
            $table->number('type') ->nullable();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('type_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('unittypes');
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        });


        Schema::create('unitpeoples', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('cpf')->unique()->nullable(); 
            $table->date('birthday')->nullable(); 
            $table->unsignedBigInteger('unit_id'). 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        });


        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        });

        Schema::create('unitgoods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->unsignedBigInteger('good_id'); 
            $table->unsignedBigInteger('unit_id'). 
            $table->unsignedBigInteger('created_by'); 
            $table->foreing('good_id') ->references('id')->on('goods'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('unitvehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('color')->nullable();
            $table->string('plate')->nullable(); 
            $table->string('register')->nullable(); 
            $table->string('renavan')->nullable(); 
            $table->date('year')->nullable;            
            $table->unsignedBigInteger('unit_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
        Schema::create('unitpets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('race'); 
            $table->string('color'); 
            $table->unsignedBigInteger('unit_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });


        Schema::create('walls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body'); 
            $table->unsignedBigInteger('created_by');            
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

         Schema::create('walllikes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('wall_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wall_id')->referecnes('id')->on('walls'); 
            $table->timestamps();
        });

        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('url'); 
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('url'); 
            $table->unsignedBigInteger('user_id'); 
            $table->insigeneBigInteger('unit_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->timestamps();
        });


        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('billets');
        Schema::dropIfExists('docs');
        Schema::dropIfExists('walllikes');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('unitpets');
        Schema::dropIfExists('unitvehicles'); 
        Schema::dropIfExists('unitgoods');
        Schema::dropIfExists('units');
        Schema::dropIfExists('goods');
        Schema::dropIfExists('unitpeoples');
        Schema::dropIfExists('unittypes');
        Schema::dropIfExists('types');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');        
    }
}
