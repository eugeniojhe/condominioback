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
       
        
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('phone')->nullable();
            $table->string('cpf')->unique(); 
            $table->unsignedSmallInteger('age')->nullable(); 
            $table->string('email')->unique()->nullable();; 
            $table->string('avatar')->default('userdefault.png'); 
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();             
            $table->timestamps();
        });


        

        Schema::create('states',function(Blueprint $table){
            $table->id(); 
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');            
        });
                
        Schema::create('cities', function(Blueprint $table){
            $table->id(); 
            $table->string('name');
            $table->unsignedBigInteger('state_id')->nullable(); 
            $table->unsignedBigInteger('created_by');
            $table->foreign('state_id')->references('id')->on('states'); 
            $table->foreign('created_by')->references('id')->on('users'); 

        });        
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('cnpj')->unique()->nullable();
            $table->string('inscricao')->unique()->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('site')->nullable();
            $table->string('address')->nullable(); 
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('bairro')->nullable(); 
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('phone')->nullable(); 
            $table->string('counter')->nullable();
            $table->string('crc')->nullable(); 
            $table->string('logo')->default('logodefault.png');  
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states'); 
            $table->timestamps();
        });
       
        Schema::create('unittypes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->foreign('company_id')->references('id')->on('companies');             
            $table->timestamps();
        });


        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->string('details'); 
            $table->smallInteger('area')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable(); 
            $table->unsignedBigInteger('type_id')->nullable(); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('unittypes');
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->timestamps();
        });


         Schema::create('unitpeoples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->string('cpf')->unique()->nullable(); 
            $table->date('birthday')->nullable();
            $table->string('photo')->default('peopledefault.png');  
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');           
            $table->timestamps();
        });


       Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->timestamps();
        });

      Schema::create('unitgoods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->unsignedBigInteger('good_id'); 
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('created_by');
            $table->string('photo')->default('gooddefault.png');  
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('good_id') ->references('id')->on('goods'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        
        Schema::create('unitvehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->string('color')->nullable();
            $table->string('plate')->nullable(); 
            $table->string('register')->nullable(); 
            $table->string('renavan')->nullable(); 
            $table->date('year')->nullable();
            $table->string('photo')->default('vehicledefault.png');             
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('unit_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('pets', function(Blueprint $table){
            $table->id(); 
            $table->string('specie');
        });
       
        Schema::create('petraces',function(Blueprint $table) {
            $table->id();
            $table->string('race');
            $table->unsignedBigInteger('pet_id');
            $table->foreign('pet_id')->references('id')->on('pets'); 
        });

        Schema::create('unitpets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('race_id'); 
            $table->string('name');
            $table->string('race'); 
            $table->string('color'); 
            $table->string('photo')->default('petdefault.png'); 
            $table->unsignedBigInteger('created_by');
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('pet_id')->references('id')->on('pets');
            $table->foreign('race_id')->references('id')->on('petraces'); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });


        Schema::create('walls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title');
            $table->string('body'); 
            $table->unsignedBigInteger('unit_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->unsignedBigInteger('created_by');            
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

         Schema::create('walllikes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('wall_id'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('wall_id')->references('id')->on('walls'); 
            $table->timestamps();
        });

       Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title'); 
            $table->string('url'); 
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title'); 
            $table->string('url'); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('unit_id'); 
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('pets');
        Schema::dropIfExists('petraces');
        Schema::dropIfExists('unitvehicles'); 
        Schema::dropIfExists('unitgoods');
        Schema::dropIfExists('goods');
        Schema::dropIfExists('unitpeoples');        
        Schema::dropIfExists('units');
        Schema::dropIfExists('unittypes');
        Schema::dropIfExists('companyusers'); 
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');  
        Schema::dropIfExists('companies');      
        Schema::dropIfExists('users');
    }
}
