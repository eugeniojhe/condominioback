<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllNewTables extends Migration
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
            $table->unsignedInteger('company_id')->nullable(); 
            $table->string('name'); 
            $table->string('phone')->nullable();
            $table->string('cpf')->unique()->nullable(); 
            $table->unsignedSmallInteger('age')->nullable(); 
            $table->string('email')->unique();; 
            $table->string('avatar')->default('userdefault.png'); 
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken(); 
           // $table->foreign('company_id')->references('id')->on('companies'); 
            $table->timestamps();
        });

        Schema::create('states',function(Blueprint $table){
            $table->id(); 
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();           
        });
                
        Schema::create('cities', function(Blueprint $table){
            $table->id(); 
            $table->string('name');
            $table->unsignedBigInteger('state_id'); 
            $table->unsignedBigInteger('created_by');
            $table->foreign('state_id')->references('id')->on('states'); 
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        });   

        Schema::create('banks', function(Blueprint $table){
            $table->id(); 
            $table->unsignedBigInteger('bank_cod')->unique; 
            $table->string('name');
            $table->string('url')->nullable(); 
            $table->timestamps();
        });  
        
        Schema::create('agencies', function(Blueprint $table){
            $table->id(); 
            $table->unsignedBigInteger('agend_cod')->unique; 
            $table->string('name');
            $table->string('url')->nullable(); 
            $table->unsignedBigInteger('bank_id');
            $table->foreignt('bank_id')->references('id')->on('banks'); 
            $table->timestamps();
        });  
        
                       
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('trading')->nullable(); //Nome fantasia - Fantasy name 
            $table->string('description')->nullable(); 
            $table->unsignedBigInteger('companytype_id'); 
            $table->string('cpf_cnpj')->unique()->nullable();
            $table->string('inscricao')->unique()->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('site')->nullable();
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('address_numer')->nullable();  
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('bairro')->nullable(); 
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();  
            $table->string('mobile_1')->nullable(); 
            $table->string('mobile_2')->nullable(); 
            $table->string('counter')->nullable();
            $table->string('crc')->nullable(); 
            $table->enum('tax_types',['diary','monthly','yearly']);
            $table->float('interest_tax')->nullable(); //Taxa de juros - Interest tax  
            $table->enum('fee_types',['percentage','value']);
            $table->float('fee_rate')->nullable();//Multa por atraso            
            $table->enum('charge_type',['fixed','prorated']);//Fixa ou ratiada //
            $table->float('condominium_value')->nullable(); //Valor do condominio
            $table->enum('reserve_fund_type',['percentage','value']); 
            $table->float('reserve_fund_value')->nullable(); //Fundo reserva //Valor ou fixo
            $table->enum('discount_type',['percentage','value']); 
            $table->float('discount_value')->nullable(); 
            $table->smallInteger('discount_days')->nullable(); //Dias para descounto 
            $table->smallInteger('expiration_day')->nullable(); //Dia vencimento do boleto  
            $table->string('logo')->default('logodefault.png');
            $table->boolean('has_block'); //Condominio tem bloco 
            $table->unsignedBigInteger('bank_slip')->nullable();//Bank to emission slip 
            $tabçe->unsignedBigInteger('agency_slip')->nullable(); 
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');  
            $table->foreign('bank_slip')->references('id')->on('banks'); 
            $table->foreing('agency_slip')->references('id')->on('agencies'); 
            $table->timestamps();
        });


        Schema::create('marital_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        }); 

        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        }); 


        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');           
            $table->string('description')->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('cpf_cnpj')->unique()->nullable();
            $table->string('inscricao')->unique()->nullable();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();  
            $table->string('mobile_1')->nullable(); 
            $table->string('mobile_2')->nullable();
            $table->boolean('access_portal')->default('1');//Allow access portal
            $table->boolean('update_data')->default('1'); //Update data at next access
            $table->string('portal_user')->nullable();
            $table->string('password_portal')->nullable();
            $table->string('identity_registration')->nullable();  
            $table->string('agency_emiter')->nullable(); 
            $table->unsignedBigInteger('gender_id')->nullable; 
            $table->unsignedBigInteger('marital_status_id');
            $table->data('birthday')->nullable(); 
            $table->string('occupation')->nullable(); 
            $table->string('workplace')->nullable(); 
            $table->string('worplace_phone')->nullable(); 
            $table->string('spouse')->nullable();//Conjuge
            $table->string('spouse_ocupation') ->nullable();
            $table->string('spouse_cpf')->nullable(); 
            $table->string('spouse_ident_registration'); 
            $table->string('spouse_agency_emiter')->nullable(); 
            $table->data('spouse_birthday')->nullable(); 
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('address_numer')->nullable(); 
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('bairro')->nullable(); 
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('marital_status_id')->references('id')->on('marital_status'); 
            $table->foreign('gender_id')->references('id')->on('genders'); 
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');  
            $table->timestamps();
        });



        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('created_by');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('created_by')->references('id')->on('users');             
            $table->timestamps();             
        });
       

        Schema::create('company_users',function(Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['company_id','user_id']); 
            $table->timestamps(); 
        });


        Schema::create('bloks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->string('details')->nullable(); 
            $table->smallInteger('area')->nullable();
            $table->foreing('company_id')->references('id')->on('companies'); 
            $table->timestamps(); 
           

        });


        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->string('details')->nullable(); 
            $table->smallInteger('area')->nullable();
            $table->float('ideal_fraction')->nullable(); //Rateio valor condominio  
            $table->string('phone',15)->nullable(); 
            $table->boolean('different_value')->default('0'); //Charge different value 
            $table->float('comdominiun_value')->nullable(); //Value condominium 
            $table->boolean('specific_day')->default('0'); //Experiration day specif 
            $table->char('experition_day')->nullable(); //Specific experation day 
            $table->boolean('allows_reservation')->default('1');//Allows reservation from condominium area 
            $table->boolean('charge_reservation')->default('1');//Charge the reservation area
            $table->char('reserve_payer');//Payer of reservation fund - Owner o tenant 
            $table->unsignedBigInteger('block_id')->nullable();
            $table->foreign('block_id')->references('id')->on('blocks');









            $table->unsignedBigInteger('owner_id')->nullable(); 
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();  
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('unittypes');
            $table->foreign('address_id') ->references('id')->on('unit_addresses'); 
            $table->foreign('created_by')->references('id')->on('users');
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
         
        Schema::dropIfExists('company_users'); 
        Schema::dropIfExists('contacts'); 
        Schema::dropIfExists('companies'); 
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states'); 
        Schema::dropIfExists('users');
    }
}
