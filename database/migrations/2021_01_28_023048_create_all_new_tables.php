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
            $table->unsignedTinyInteger('age')->nullable(); 
            $table->string('email')->unique();; 
            $table->string('avatar')->default('userdefault.png'); 
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken(); 
            $table->foreign('company_id')->references('id')->on('companies'); 
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
            $table->unsignedTinyInteger('area')->nullable();
            $table->unsignedTinyInteger('elevators')->nullble(); 
            $table->unsignedTinyInteger('garages')->nullable(); 
            $table->date('foundation_date')->nullable(); 
            $table->string('site')->nullable();
            $table->string('zip_code')->nullable(); 
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('address_number')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('state')->nullable(); 
            $table->string('neighborhood')->nullable(); 
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
            $table->unsignedTinyInteger('discount_days')->nullable(); //Dias para descounto 
            $table->unsignedTinyInteger('expiration_day')->nullable(); //Dia vencimento do boleto  
            $table->string('logo')->default('logodefault.png');
            $table->boolean('has_block'); //Condominio tem bloco 
            $table->unsignedTinyInteger('bloks')->nullable(); //Total blocks 
            $table->unsignedTinyInteger('apartments')->nullable(); //Total Aptos 
            $table->unsignedBigInteger('bank_slip')->nullable();//Bank to emission slip 
            $tabçe->unsignedBigInteger('agency_slip')->nullable(); 
            $table->unsignedBigInteger('created_by')->nullable();             
            $table->foreign('bank_slip')->references('id')->on('banks'); 
            $table->foreing('agency_slip')->references('id')->on('agencies'); 
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

        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->enum('type',[1,2])->nullable();//1-Credit 2-Debit 
            $table->unsignedBigInteger('created_by');
            $table->unsignedTinyInteger('status')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        }); 

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('agency_id');
            $table->unsignedTinyInteger('account_number')->nullable(); 
            $table->unsignedTinyInteger('account_digit')->nullable(); 
            $table->string('account_holder')->nullable();  
            $table->enum('type',[1,2])->nullable();//1-Credit 2-Debit 
            $table->unsignedBigInteger('created_by');
            $table->smallInteger('status')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('bank_id')->references('id')->on('banks'); 
            $table->foreign('agency_id')->references('id')->on('agencies'); 
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
            $table->unsignedTinyInteger('address_numer')->nullable(); 
            $table->unsignedBigInteger('city_id')->nullable(); 
            $table->string('neighborhood')->nullable(); 
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('marital_status_id')->references('id')->on('marital_status'); 
            $table->foreign('gender_id')->references('id')->on('genders'); 
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
            $table->string('zip_code')->nullable(); 
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('address_number')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('state')->nullable(); 
            $table->string('neighborhood')->nullable(); ;
            $table->unsignedTinyInteger('address_numer')->nullable(); 
             $table->string('neighborhood')->nullable();            
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('marital_status_id')->references('id')->on('marital_status'); 
            $table->foreign('gender_id')->references('id')->on('genders'); 
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
       

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->string('details')->nullable(); 
            $table->unsignedTinyInteger('area')->nullable();
            $table->foreing('company_id')->references('id')->on('companies'); 
            $table->timestamps(); 
        });


        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('block_id'); 
            $table->string('name'); 
            $table->string('details')->nullable(); 
            $table->unsignedTinyInteger('area')->nullable();
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

    Schema::create('stakeholders_status', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('title'); 
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
        $table->string('zip_code')->nullable(); 
        $table->string('address')->nullable();
        $table->unsignedTinyInteger('address_number')->nullable(); 
        $table->string('city')->nullable(); 
        $table->string('state')->nullable(); 
        $table->string('neighborhood')->nullable(); 
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();  
        $table->string('mobile_1')->nullable(); 
        $table->string('mobile_2')->nullable();         
        $table->string('logo')->default('logodefault.png');    
        $table->unsigneBigInteger('standart_account');  
        $table->unsignedTinyInteger('status')->nullable();      
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('standart_account')->references('id')->on('financial_accounts'); 
        $table->foreign('status')->references('id')->on('stakeholders_status');         
        $table->timestamps();
    });


    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('name'); 
        $table->string('trading')->nullable(); //Nome fantasia - Fantasy name 
        $table->string('description')->nullable(); 
        $table->string('cpf_cnpj')->unique()->nullable();
        $table->string('inscricao')->unique()->nullable(); 
        $table->string('email')->unique()->nullable();
        $table->string('site')->nullable();
        $table->string('zip_code')->nullable(); 
        $table->string('address')->nullable();
        $table->unsignedTinyInteger('address_number')->nullable(); 
        $table->string('city')->nullable(); 
        $table->string('state')->nullable(); 
        $table->string('neighborhood')->nullable(); 
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();  
        $table->string('mobile_1')->nullable(); 
        $table->string('mobile_2')->nullable();         
        $table->string('logo')->default('logodefault.png');    
        $table->unsigneBigInteger('standart_account');  
        $table->unsignedTinyInteger('status')->nullable();      
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('standart_account')->references('id')->on('financial_accounts'); 
        $table->foreign('status')->references('id')->on('stakeholders_status');         
        $table->timestamps();
    });

    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('name');    //ok       
        $table->string('description')->nullable(); //ok
        $table->string('email')->unique()->nullable();//ok
        $table->string('cpf_cnpj')->unique()->nullable();//ok
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();  
        $table->string('mobile_1')->nullable(); 
        $table->string('mobile_2')->nullable();
        $table->boolean('access_portal')->default('1');//Allow access portal
        $table->boolean('update_data')->default('1'); //Update data at next access
        $table->string('portal_user')->nullable();
        $table->string('password_portal')->nullable();
        $table->string('identity_registration')->nullable();// ok 
        $table->string('agency_emiter')->nullable(); //ok
        $table->date('admission_date')->nullable();
        $table->date('dismissed_date')->nullable();
        $table->boolean('show_data_portal')->nullable();
        $table->string('crc')->nullable();      
        $table->unsignedBigInteger('gender_id')->nullable; //ok 
        $table->unsignedBigInteger('marital_status_id'); //ok 
        $table->data('birthday')->nullable(); //ok
        $table->string('occupation')->nullable(); 
        $table->string('workplace')->nullable(); 
        $table->string('zip_code')->nullable(); 
        $table->string('address')->nullable();
        $table->unsignedTinyInteger('address_number')->nullable(); 
        $table->string('city')->nullable(); 
        $table->string('state')->nullable(); 
        $table->string('neighborhood')->nullable();         
        $table->string('spouse')->nullable(); 
        $table->string('spouse_identity_registration')->nullable();// ok 
        $table->string('spouse_agency_emiter')->nullable(); //ok
        $table->string('spouse_cpf')->nullable();
        $table->date('spouse_birthday')->nullable(); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('marital_status_id')->references('id')->on('marital_status'); 
        $table->foreign('gender_id')->references('id')->on('genders'); 
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });

    Schema::create('polls', function (Blueprint $table) {
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


     Schema::create('poll_alternatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigIntger('company_id'); 
            $table->string('title');
            $table->unsignedBigInteger('poll_id');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('poll_id')->references('id')->on('poll_items'); 
        });

      Schema::create('poll_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('name');
            $table->string('email');
            $table->unsignedBigInteger('alternative_id');
            $table->unique(['alternative_id','email']); 
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('alternative_id')->references('id')->on('alternatives'); 
        });


        Schema::create('poll_correct_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('alternative_id');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('alternative_id')->references('id')->on('alternatives'); 
        });
   

    

    Schema::create('areas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id')->nullable(); 
        $table->string('title'); 
        $table->string('cover');
        $table->string('days');
        $table->integer('status')->default(1);
        $table->time('start_time'); 
        $table->time('end_time'); 
        $table->enum('charge_type',['fixe','percent']);//Charge by a fixe value or percent on value condominy
        $table->string('text')->nullable(); //Text to show when area is rented
        $table->boolean('include_file')->default(true); //Include file about utilization rules
        $table->string('url_file')->nullable();
        $table->boolean('automatic_confirmation')->default(false); 
        $table->boolean('hasaditional_value')->default(false);
        $table->double('aditional_value')->nullable(); 
        $table->string('description_value')->nullable(); 
        $string->smallInteger('advance_days')->nullable();//How much days before reservation area 
        $table->unsignedBigInteger('created_by');             
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('unit_id')->references('id')->on('units');          
    });


    Schema::create('areadisabledays', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('area_id'); 
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id');
        $table->string('title'); 
        $table->date('day'); 
        $table->time('start_time'); 
        $table->time('end_time'); 
        $table->unsignedBigInteger('created_by'); 
        $table->foreign('area_id')->references('id')->on('areas'); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('unit_id')->references('id')->on('units');          
    });


    Schema::create('visitors', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('name');    //ok       
        $table->string('email')->unique()->nullable();//ok
        $table->string('cpf_cnpj')->unique()->nullable();//ok
        $table->string('phone_1')->nullable();  
        $table->string('identity_registration')->nullable();// ok 
        $table->string('agency_emiter')->nullable(); //ok        
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });


    Schema::create('status', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('description'); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });

    //Tipos de rateio 
    Schema::create('prorates', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('description'); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });

    Schema::create('accounts_payables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
         $table->unsignedBigInteger('document_type')->nullable();
        $table->unsignedInteger('document');
        $table->unsignedBigInteger('provider_id')->nullble(); 
        $table->date('emission_date');
        $table->unsignedBigInteger('financial_accounts_deb');
        $table->unsignedBigInteger('financial_accounts_cre');
        $table->string('notes')->nullable();
        $table->float('amount');
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable(); 
        $table->unsignedSmallInteger('installment_plan')->nullable();//Parcelas 
        $table->unsignedSmallInteger('status_id')->nullable(); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreing('status_id')->references('id')->on('status'); 
        $table->timestamps();
    });

    Schema::create('item_account_payables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('account_payable_id'); 
        $table->date('expiration_date')->nullable();
        $table->data('payday')->nullable(); 
        $table->string('notes')->nullable();
        $table->float('amount');
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable();  
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsigneBigInteger('status')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('account_payable_id')->references('id')->on('accounts_payable'); 
        $table->foreign('created_by')->references('id')->on('users'); 
        $table->foreing('status_id')->references('id')->on('status');  
        $table->timestamps();
    });

    Schema::create('attachments_payable', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('account_payable_id'); 
        $table->string('notes')->nullable();
        $table->string('url')->nullable(); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('account_payable_id')->references('id')->on('accounts_payable'); 
        $table->timestamps();
    });


    Schema::create('accounts_receivables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('customer_id')->nullable(); 
        $table->unsignedBigInteger('unit_id')->nullable(); 
        $table->unsignedBigInteger('document_type')->nullable();
        $table->unsignedInteger('document');
        $table->unsignedBigInteger('provider_id')->nullble(); 
        $table->date('emission_date');        
        $table->unsignedBigInteger('financial_accounts_deb');
        $table->unsignedBigInteger('financial_accounts_cre');
        $table->string('notes')->nullable();
        $table->float('amount');
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable();
        $table->boolean('has_percent')->default(false); 
        $table->float('percent',3,2)->nullable();//Percentual do valor do condominio a ser cobrado  
        $table->unsignedSmallInteger('prorate_id')->nullable();//Tipo de rateio; 
        $table->unsignedSmallInteger('installment_plan')->nullable();//Parcelas 
        $table->unsignedSmallInteger('status')->nullable(); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('customer_id')->references('id')->on('customers');
        $table->foreign('unit_id')->references('id')->on('units');  
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreing('status_id')->references('id')->on('status'); 
        $table->timestamps();
    });

    Schema::create('items_accounts_receivable', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('account_receivable_id'); 
        $table->date('expiration_date')->nullable();
        $table->data('payday')->nullable();  
        $table->string('notes')->nullable();
        $table->float('amount');
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable();  
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsigneBigInteger('status')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('account_receivable_id')->references('id')->on('accounts_receivable'); 
        $table->foreign('created_by')->references('id')->on('users'); 
        $table->foreing('status_id')->references('id')->on('status');  
        $table->timestamps();
    });


   
    Schema::create('attachments_receivable', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('account_payable_id'); 
        $table->string('notes')->nullable();
        $table->string('url')->nullable(); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('account_receivable_id')->references('id')->on('accounts_receivable'); 
        $table->timestamps();
    });

    Schema::create('consumption_types', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('title')->nullable();
        $table->float('conversion_factor')->nullable(); 
        $table->flate('minimum_rate')->nullable();         
        $table->unsignedBigInteger('created_by')->nullable();
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('account_receivable_id')->references('id')->on('accounts_receivable'); 
        $table->timestamps();
    });


    Schema::create('consumptions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedTinyInteger('month')->nullable(); 
        $table->year('year')->nullable(); 
        $table->unignedInteger('previous_reading')->nullable();
        $table->unsinedInteger('current_reading')->nullable(); 
        $table->datetime('date_reading')->nullable(); 
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unique(['month','year']); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('account_receivable_id')->references('id')->on('accounts_receivable'); 
        $table->timestamps();
    });

    Schema::create('walls', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('title');
        $table->string('body')->nullable(); 
        $table->date('expiration_date')->nullable(); 
        $table->unsignedBigInteger('unit_id');
        $table->unsignedBigInteger('created_by'); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('unit_id')->references('id')->on('units'); 
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
        $table->string('url')->nullable(); 
        $table->unsignedBigInteger('user_id'); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('user_id')->references('id')->on('users');
        $table->timestamps();
    });
    Schema::create('billets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id')->nullable(); 
        $table->string('title'); 
        $table->string('url'); 
        $table->unsignedBigInteger('created_by'); 
         $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('unit_id')->references('id')->on('units'); 
        $table->timestamps();
    });

    Schema::create('warnings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id');
        $table->string('title'); 
        $table->string('status')->default('IN_REVIEW'); //Resolved 
        $table->text('photos'); 
        $table->date('expiration_date')->nullable(); 
        $table->unsignedBigInteger('created_by');             
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('unit_id')->references('id')->on('units'); 
        $table->timestamps();
    });

    Schema::create('lostfounds', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id');
        $table->string('photo');
        $table->string('description');
        $table->string('status')->default('lost'); //Found
        $table->string('solution')->default('em andamento'); 
        $table->date('solution_date')->nullable(); 
        $table->unsignedBigInteger('created_by');             
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('unit_id')->references('id')->on('units');          
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
