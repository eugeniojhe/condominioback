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
        
        Schema::create('banck_agencies', function(Blueprint $table){
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
            $table->unsignedSmallInteger('area')->nullable();
            $table->unsignedSmallInteger('elevators')->nullble(); 
            $table->unsignedSmallInteger('garages')->nullable(); 
            $table->date('foundation_date')->nullable(); 
            $table->string('site')->nullable();
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('address_numer')->nullable();  
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('neighborhood')->nullable(); 
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
            $table->smallInteger('bloks')->nullable(); //Total blocks 
            $table->smallInteger('apartments')->nullable(); //Total Aptos 
            $table->unsignedBigInteger('bank_slip')->nullable();//Bank to emission slip 
            $tabçe->unsignedBigInteger('agency_slip')->nullable(); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');  
            $table->foreign('bank_slip')->references('id')->on('banks'); 
            $table->foreing('agency_slip')->references('id')->on('agencies'); 
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        });


        Schema::create('marital_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        }); 

        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        }); 


        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
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
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('address_numer')->nullable(); 
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('neighborhood')->nullable(); 
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('marital_status_id')->references('id')->on('marital_status'); 
            $table->foreign('gender_id')->references('id')->on('genders'); 
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states'); 
            $table->foreign('created_by')->references('id')->on('users');  
            $table->timestamps();
        });


        Schema::create('relatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('tenant_id');
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
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('address_numer')->nullable(); 
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('neighborhood')->nullable();            
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('tenant_id')->references('id')->on('tenants');
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


        Schema::create('blocks', function (Blueprint $table) {
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
            $table->unsignedBigInteger('block_id'); 
            $table->string('name'); 
            $table->string('details')->nullable(); 
            $table->smallInteger('area')->nullable();
            $table->float('ideal_fraction')->nullable(); //Rateio valor condominio  
            $table->string('phone',15)->nullable(); 
            $table->boolean('different_value')->default('0'); //Charge different value 
            $table->float('comdominiun_value')->nullable(); //Value condominium 
            $table->boolean('specific_day')->default('0'); //Experiration day specif 
            $table->char('expiration_day')->nullable(); //Specific experation day 
            $table->boolean('allows_reservation')->default('1');//Allows reservation from condominium area 
            $table->boolean('charge_reservation')->default('1');//Charge the reservation area
            $table->char('reserve_payer');//Payer of reservation fund - Owner o tenant 
            $table->char('status')->nullable(); 
            $table->string('tags')->nullable(); 
            $table->boolean('bill_by_email')->default('1');//Receive bill by email;
            $table->unsignedBigInteger('created_by')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });


        Schema::create('unit_tenants', function (Blueprint $table) {
             $table->unsignedBigInteger('unit_id');
             $table->unsignedBigInteger('company_id');
             $table->unsignedBigInteger('tenant_id');
             $table->boolean('is_owner')->default('0');
             $table->string('relationship_unit');//Relationship - Lives, Onwer etc  
             $table->date('entry_date')->nullable();
             $table->date('departure_date')->nullable();
             $table->date('purchased_date')->nullable(); 
             $table->foreign('unit_id')->references('id')->on('units'); 
             $table->foreign('company_id')->references('id')->on('companies');
             $table->foreign('tenant_id')->references('id')->on('tenants'); 
             $table->timestamps(); 
        });

        Schema::create('unit_relatives', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('relative_id'); 
            $table->boolean('is_owner')->default('0'); 
            $table->string('relationship_unit');//Relationship - Lives, Onwer etc  
            $table->date('entry_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('purchased_date')->nullable(); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('relative_id')->references('id')->on('relatives'); 
            $table->timestamps(); 
       });

       Schema::create('unitvehicles', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('unit_id'); 
        $table->unsignedBigInteger('company_id');
        $table->string('name'); 
        $table->string('description')->nullable(); 
        $table->string('color')->nullable();
        $table->string('plate')->nullable(); 
        $table->string('register')->nullable(); 
        $table->string('renavan')->nullable(); 
        $table->date('year')->nullable();
        $table->string('photo')->default('vehicledefault.png');             
        $table->unsignedBigInteger('created_by'); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('unit_id')->references('id')->on('units'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->timestamps();
    });

    Schema::create('unitpets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id');
        $table->string('name');
        $table->string('race'); 
        $table->string('color'); 
        $table->string('photo')->default('petdefault.png'); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('unit_id')->references('id')->on('units'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->timestamps();
    });




    
    Schema::create('financial_accounts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->enum('type',[1,2])->nullable();//1-Credit 2-Debit 
        $table->unsignedBigInteger('created_by');
        $table->smallInteger('status')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->timestamps();
    });


    Schema::create('providers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('name'); 
        $table->string('trading')->nullable(); //Nome fantasia - Fantasy name 
        $table->string('description')->nullable(); 
        $table->string('cpf_cnpj')->unique()->nullable();
        $table->string('inscricao')->unique()->nullable(); 
        $table->string('email')->unique()->nullable();
        $table->string('site')->nullable();
        $table->string('address')->nullable();
        $table->unsignedSmallInteger('address_numer')->nullable();  
        $table->unsignedBigInteger('city_id')->nullable(); 
        $table->string('neighborhood')->nullable(); 
        $table->unsignedBigInteger('state_id')->nullable();
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();  
        $table->string('mobile_1')->nullable(); 
        $table->string('mobile_2')->nullable(); 
        $table->unsigneBigInteger('standart_account');
        $table->string('logo')->default('logodefault.png');    
        $table->foreign('standart_account')->references('id')->on('financial_accounts'); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('city_id')->references('id')->on('cities');
        $table->foreign('state_id')->references('id')->on('states');  
        $table->foreign('created_by')->references('id')->on('users'); 
        $table->smallInteger('status')->nullable(); 
        $table->timestamps();
    });

    Schema::create('bank_accounts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('bank_id');
        $table->unsignedBigInteger('agency_id');
        $table->unsignedInteger('account_number')->nullable(); 
        $table->unsignedSmallInteger('account_digit')->nullable(); 
        $table->string('account_holder')->nullable();  
        $table->enum('type',[1,2])->nullable();//1-Credit 2-Debit 
        $table->unsignedBigInteger('created_by');
        $table->smallInteger('status')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->timestamps();
    });

    Schema::create('poll', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('title'); 
        $table->string('description');  
        $table->enum('acces',['all','tanants','owners'])->nullable();
        $table->datetime('start_date')->nullable(); 
        $table->datetime('end_date')->nullable(); 
        $table->boolean('single_vote')->default('1'); //Unique vote by unit 
        $table->unsignedBigInteger('created_by');
        $table->smallInteger('status')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->timestamps();
    });

    Schema::create('poll_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('poll_id');
        $table->string('question');
        $table->enum('choice',['single','multiple','free-text']);
        $table->string('alternatives');
        $table->smallInteger('status')->nullable(); 
        $table->foreign('poll_id')->references('id')->on('polls');
        $table->foreign('company_id')->references('id')->on('companies');        
    });

    Schema::create('profiles', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('title'); 
        $table->string('icon')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->smallInteger('status')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies'); 
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
