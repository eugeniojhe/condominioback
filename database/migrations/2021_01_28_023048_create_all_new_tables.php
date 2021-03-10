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
        
        Schema::create('banks', function(Blueprint $table){
            $table->id(); 
            $table->unsignedBigInteger('bank')->unique; 
            $table->string('name',100);
            $table->string('url')->nullable(); 
            $table->timestamps();
        });  
        
        Schema::create('bankagencies', function(Blueprint $table){
            $table->id(); 
            $table->unsignedBigInteger('bank'); 
            $table->unsignedBigInteger('agency'); 
            $table->string('name',100);
            $table->string('url')->nullable(); 
            $table->unsignedBigInteger('bank_id');
            $table->unique(['bank','agency']);
            $table->foreign('bank_id')->references('id')->on('banks'); 
            $table->timestamps();
        });  
        
                       
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',100); 
            $table->string('trading',100)->nullable(); //Nome fantasia - Fantasy name 
            $table->string('description')->nullable(); 
            $table->unsignedBigInteger('companytype_id')->nullable(); 
            $table->string('cpf_cnpj',30)->unique()->nullable();
            $table->string('inscricao',30)->unique()->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->unsignedSmallInteger('area')->nullable();
            $table->unsignedTinyInteger('elevators')->nullable(); 
            $table->unsignedTinyInteger('garages')->nullable(); 
            $table->date('foundation_date')->nullable(); 
            $table->string('site')->nullable();
            $table->string('zip_code',8)->nullable(); 
            $table->string('address',100)->nullable();
            $table->unsignedSmallInteger('address_number')->nullable(); 
            $table->string('city',100)->nullable(); 
            $table->string('state',50)->nullable(); 
            $table->string('neighborhood',100)->nullable(); 
            $table->string('phone_1',20)->nullable();
            $table->string('phone_2',20)->nullable();  
            $table->string('mobile_1',20)->nullable(); 
            $table->string('mobile_2',20)->nullable(); 
            $table->string('counter')->nullable();
            $table->string('crc',20)->nullable(); 
            $table->enum('tax_types',['diary','monthly','yearly'])->nullable();
            $table->float('interest_tax')->nullable(); //Taxa de juros - Interest tax  
            $table->enum('fee_types',['percentage','value'])->nullable();
            $table->float('fee_rate')->nullable();//Multa por atraso            
            $table->enum('charge_type',['fixed','prorated'])->nullable();//Fixa ou ratiada //
            $table->float('condominium_value')->nullable(); //Valor do condominio
            $table->enum('reserve_fund_type',['percentage','value'])->nullable(); 
            $table->float('reserve_fund_value')->nullable(); //Fundo reserva //Valor ou fixo
            $table->enum('discount_type',['percentage','value'])->nullable(); 
            $table->float('discount_value')->nullable(); 
            $table->unsignedTinyInteger('discount_days')->nullable(); //Dias para descounto 
            $table->unsignedTinyInteger('expiration_day')->nullable(); //Dia vencimento do boleto  
            $table->string('logo')->default('logodefault.png');
            $table->boolean('has_block')->nullable(); //Condominio tem bloco 
            $table->unsignedTinyInteger('blocks')->nullable(); //Total blocks 
            $table->unsignedSmallInteger('apartments')->nullable(); //Total Aptos 
            $table->unsignedBigInteger('bank_slip')->nullable();//Bank to emission slip 
            $table->unsignedBigInteger('bankagency_slip')->nullable(); 
            $table->unsignedBigInteger('created_by')->nullable();             
            $table->foreign('bank_slip')->references('id')->on('banks'); 
            $table->foreign('bankagency_slip')->references('id')->on('bankagencies'); 
           // $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable(); 
            $table->string('name',100); 
            $table->string('phone',20)->nullable();
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


        Schema::create('companyusers',function(Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->primary(['company_id','user_id']); 
            $table->timestamps(); 
        });
    
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('description'); 
            $table->unsignedBigInteger('created_by');
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('created_by')->references('id')->on('users');  
            $table->timestamps();
        });

       
        Schema::create('financialaccounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->enum('type',[1,2])->nullable();//1-Credit 2-Debit 
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('status_id')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status'); 
            $table->timestamps();
        }); 

        Schema::create('bankaccounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('bankagency_id');
            $table->unsignedSmallInteger('account_number')->nullable(); 
            $table->unsignedSmallInteger('account_digit')->nullable(); 
            $table->string('account_holder')->nullable();  
            $table->enum('type',[1,2])->nullable();//1-Credit 2-Debit 
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('status_id')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('bank_id')->references('id')->on('banks'); 
            $table->foreign('bankagency_id')->references('id')->on('bankagencies');
            $table->foreign('status_id')->references('id')->on('status'); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('maritalstatus', function (Blueprint $table) {
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
            $table->unsignedBigInteger('company_id'); 
            $table->string('name');           
            $table->string('description')->nullable(); 
            $table->string('email')->unique()->nullable();
            $table->string('cpf_cnpj',15)->unique()->nullable();
            $table->string('status_subscription')->unique()->nullable();           
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();  
            $table->string('mobile_1')->nullable(); 
            $table->string('mobile_2')->nullable();
            $table->boolean('access_portal')->default('1');//Allow access portal
            $table->boolean('update_date')->default('1'); //Update data at next access
           // $table->string('user_portal')->nullable();
            //$table->string('password_portal')->nullable();/
            $table->unsignedBigInteger('user_id')->nullable(); //Usuario no portal //
            $table->string('identity_registration')->nullable();  
            $table->string('agency_emiter')->nullable(); 
            $table->unsignedBigInteger('gender_id')->nullable(); 
            $table->unsignedBigInteger('maritalstatus_id')->nullable();
            $table->date('birthday')->nullable(); 
            $table->string('occupation')->nullable(); 
            $table->string('workplace')->nullable(); 
            $table->string('zip_code')->nullable(); 
            $table->string('address')->nullable();
            $table->unsignedSmallInteger('address_number')->nullable(); 
            $table->string('city')->nullable(); 
            $table->string('state')->nullable(); 
            $table->string('neighborhood')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('maritalstatus_id')->references('id')->on('maritalstatus'); 
            $table->foreign('gender_id')->references('id')->on('genders'); 
            $table->foreign('created_by')->references('id')->on('users');  
            $table->foreign('user_id')->references('id')->on('users');
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
            $table->unsignedBigInteger('maritalstatus_id');
            $table->date('birthday')->nullable(); 
            $table->string('occupation')->nullable(); 
            $table->string('workplace')->nullable(); 
            $table->string('zip_code')->nullable(); 
            $table->string('address')->nullable();
            $table->string('city')->nullable(); 
            $table->string('state')->nullable(); 
            $table->string('neighborhood')->nullable();
            $table->unsignedSmallInteger('address_numer')->nullable(); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('maritalstatus_id')->references('id')->on('maritalstatus'); 
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
            $table->unsignedSmallInteger('area')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('created_by')->references('id')->on('users'); 
            $table->timestamps(); 
        });


        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('block_id'); 
            $table->string('name'); 
            $table->string('details')->nullable(); 
            $table->unsignedSmallInteger('area')->nullable();
            $table->float('ideal_fraction')->nullable(); //Rateio valor condominio  
            $table->string('phone',15)->nullable(); 
            $table->boolean('different_value')->default('0'); //Charge different value 0->false 1-true 
            $table->float('comdominiun_value')->nullable(); //Value condominium 
            $table->boolean('specific_day')->default('0'); //Experiration day specif 
            $table->unsignedTinyInteger('expiration_day')->nullable(); //Specific experation day 
            $table->boolean('allows_reservation')->default('1');//Allows reservation from condominium area 
            $table->boolean('charge_reservation')->default('1');//Charge the reservation area
            $table->unsignedTinyInteger('reserve_payer')->nullable();//Payer of reservation fund - Owner o tenant 
            $table->string('tags')->nullable(); 
            $table->boolean('bill_by_email')->default('1');//Receive bill by email;
            $table->unsignedBigInteger('created_by'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('tenanttypes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name'); 
            $table->unsignedBigInteger('created_by');
            $table->foreign('company_id')->references('id')->on('companies');   
            $table->foreign('created_by')->references('id')->on('users'); 
        });


        Schema::create('unittenants', function (Blueprint $table) {
             $table->unsignedBigInteger('unit_id');
             $table->unsignedBigInteger('company_id');
             $table->unsignedBigInteger('tenant_id');
             $table->boolean('is_owner')->default('0');
             $table->unsignedBigInteger('tenanttype_id');//Relationship - Lives, Onwer etc  
             $table->unsignedBigInteger('created_by'); 
             $table->date('entry_date')->nullable();
             $table->date('departure_date')->nullable();
             $table->date('purchased_date')->nullable(); 
             $table->unique(['unit_id','company_id','tenant_id']); 
             $table->foreign('unit_id')->references('id')->on('units'); 
             $table->foreign('company_id')->references('id')->on('companies');
             $table->foreign('tenant_id')->references('id')->on('tenants'); 
             $table->foreign('tenanttype_id')->references('id')->on('tenanttypes'); 
             $table->foreign('created_by')->references('id')->on('users'); 
             $table->timestamps(); 
        });

        Schema::create('unitrelatives', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('relative_id'); 
            $table->unsignedBigInteger('created_by'); 
            $table->boolean('is_owner')->default('0'); 
            $table->string('relationship_unit');//Relationship - Lives, Onwer etc  
            $table->date('entry_date')->nullable();
            $table->date('departure_date')->nullable();
            $table->date('purchased_date')->nullable(); 
            $table->foreign('unit_id')->references('id')->on('units'); 
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('relative_id')->references('id')->on('relatives'); 
            $table->foreign('created_by')->references('id')->on('users');
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
        $table->year('year')->nullable();
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

    Schema::create('stakeholderstatus', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('title'); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users'); 
      
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
        $table->unsignedSmallInteger('address_number')->nullable(); 
        $table->string('city')->nullable(); 
        $table->string('state')->nullable(); 
        $table->string('neighborhood')->nullable(); 
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();  
        $table->string('mobile_1')->nullable(); 
        $table->string('mobile_2')->nullable();         
        $table->string('logo')->default('logodefault.png');    
        $table->unsignedBigInteger('standard_account')->nullable();  
        $table->unsignedBigInteger('stakeholderstatus_id')->nullable();      
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('standard_account')->references('id')->on('financialaccounts'); 
        $table->foreign('stakeholderstatus_id')->references('id')->on('stakeholderstatus');         
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
        $table->unsignedSmallInteger('address_number')->nullable(); 
        $table->string('city')->nullable(); 
        $table->string('state')->nullable(); 
        $table->string('neighborhood')->nullable(); 
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();  
        $table->string('mobile_1')->nullable(); 
        $table->string('mobile_2')->nullable();         
        $table->string('logo')->default('logodefault.png');    
        $table->unsignedBigInteger('standard_account')->nullable();  
        $table->unsignedBigInteger('stakeholderstatus_id')->nullable();      
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('standard_account')->references('id')->on('financialaccounts'); 
        $table->foreign('stakeholderstatus_id')->references('id')->on('stakeholderstatus');         
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
        $table->unsignedBigInteger('maritalstatus_id'); //ok 
        $table->date('birthday')->nullable(); //ok
        $table->string('occupation')->nullable(); 
        $table->string('workplace')->nullable(); 
        $table->string('zip_code')->nullable(); 
        $table->string('address')->nullable();
        $table->unsignedSmallInteger('address_number')->nullable(); 
        $table->string('city')->nullable(); 
        $table->string('state')->nullable(); 
        $table->string('neighborhood')->nullable();         
        $table->string('spouse')->nullable(); 
        $table->string('spouse_identity_registration')->nullable();// ok 
        $table->string('spouse_agency_emiter')->nullable(); //ok
        $table->string('spouse_cpf')->nullable();
        $table->date('spouse_birthday')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('maritalstatus_id')->references('id')->on('maritalstatus'); 
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

    Schema::create('pollquestions', function (Blueprint $table) {
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


     Schema::create('pollalternatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('title');
            $table->unsignedBigInteger('pollquestion_id');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('pollquestion_id')->references('id')->on('pollquestions'); 
        });

      Schema::create('pollanswers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('name');
            $table->string('email');
            $table->unsignedBigInteger('pollalternative_id');
            $table->unique(['pollalternative_id','email']); 
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('pollalternative_id')->references('id')->on('pollalternatives'); 
        });


        Schema::create('pollcorrectanswers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); 
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('pollalternative_id');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies'); 
            $table->foreign('pollalternative_id')->references('id')->on('pollalternatives'); 
        }); 
 

    Schema::create('areas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id')->nullable(); 
        $table->string('title'); 
        $table->string('cover')->nullable();
        $table->string('days')->nullable();
        $table->integer('status')->default(1);
        $tabhe->unsignedTinyInteger('previous_hours')->nullable(); //Hours before the end of free periodo 
        $table->time('start_time')->nullable(); 
        $table->time('end_time')->nullable(); 
        $table->enum('charge_type',['fixe','percent'])->nullable();//Charge by a fixe value or percent on value condominy
        $table->string('text')->nullable(); //Text to show when area is rented
        $table->boolean('include_file')->default(true); //Include file about utilization rules
        $table->string('url_file')->nullable();
        $table->boolean('automatic_confirmation')->default(false); 
        $table->boolean('hasaditional_value')->default(false);
        $table->double('aditional_value')->nullable(); 
        $table->string('description_value')->nullable(); 
        $table->smallInteger('advance_days')->nullable();//How much days before reservation area 
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


    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('area_id'); 
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('unit_id');
        $table->string('title');         
        $table->date('reservation_date'); 
        $table->time('start_time'); 
        $table->time('end_time')->nullable(); 
        $table->unsignedBigInteger('user_id'); 
        $table->foreign('area_id')->references('id')->on('areas'); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('user_id')->references('id')->on('users');
        $table->foreign('unit_id')->references('id')->on('units');  
        $table->timestamps();        
    });

    Schema::create('visitors', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('name');    //ok       
        $table->string('email')->unique()->nullable();//ok
        $table->string('cpf_cnpj')->unique()->nullable();//ok
        $table->string('photo')->nullable(); 
        $table->string('phone_1')->nullable();  
        $table->string('identity_registration')->nullable();// ok 
        $table->string('agency_emiter')->nullable(); //ok        
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });
   
    //Tipos de rateio 
    Schema::create('prorates', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('description'); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies'); 
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });

    Schema::create('documenttypes', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('serie',4)->nullable(); 
        $table->string('acronym',10)->nullable(); 
    });
  
    Schema::create('accountpayables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('documenttype_id')->nullable();
        $table->unsignedSmallInteger('document_number')->nullable(); 
        $table->unsignedBigInteger('provider_id')->nullble(); 
        $table->date('emission_date');
        $table->unsignedBigInteger('financial_account_deb')->nulable();
        $table->unsignedBigInteger('financial_account_cre')->nulable();
        $table->string('notes')->nullable();
        $table->float('amount')->nullable();
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable(); 
        $table->unsignedSmallInteger('installment_plan')->nullable();//Parcelas 
        $table->unsignedBigInteger('status_id')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('provider_id')->references('id')->on('providers'); 
        $table->foreign('documenttype_id')->references('id')->on('documenttypes'); 
        $table->foreign('financial_account_deb')->references('id')->on('financialaccounts');
        $table->foreign('financial_account_cre')->references('id')->on('financialaccounts');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('status_id')->references('id')->on('status'); 
        $table->timestamps();
    });

    Schema::create('itemaccountpayables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('accountpayable_id'); 
        $table->date('expiration_date')->nullable();
        $table->date('payday')->nullable(); 
        $table->string('notes')->nullable();
        $table->float('amount');
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable();  
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('status_id')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('accountpayable_id')->references('id')->on('accountpayables'); 
        $table->foreign('created_by')->references('id')->on('users'); 
        $table->foreign('status_id')->references('id')->on('status');  
        $table->timestamps();
    });

    Schema::create('attachmentpayables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('accountpayable_id'); 
        $table->string('notes')->nullable();
        $table->string('url')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('accountpayable_id')->references('id')->on('accountpayables'); 
        $table->timestamps();
    });


    Schema::create('accountreceivables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('customer_id')->nullable(); 
        $table->unsignedBigInteger('unit_id')->nullable(); 
        $table->unsignedBigInteger('documenttype_id');
        $table->unsignedSmallInteger('document_number')->nullble(); 
        $table->date('emission_date');        
        $table->unsignedBigInteger('financial_account_deb');
        $table->unsignedBigInteger('financial_account_cre');
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
        $table->unsignedBigInteger('status_id')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('customer_id')->references('id')->on('customers');
        $table->foreign('unit_id')->references('id')->on('units');  
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('status_id')->references('id')->on('status');
        $table->foreign('financial_account_deb')->references('id')->on('financialaccounts');
        $table->foreign('financial_account_cre')->references('id')->on('financialaccounts'); 
        $table->foreign('documenttype_id')->references('id')->on('documenttypes'); 
        $table->timestamps();
    });

    Schema::create('itemaccountreceivables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('accountreceivable_id'); 
        $table->date('expiration_date')->nullable();
        $table->date('payday')->nullable();  
        $table->string('notes')->nullable();
        $table->float('amount');
        $table->float('provision_amount')->nullable();
        $table->float('interest_value')->nullable(); //Juros 
        $table->float('discount_value')->nullable(); 
        $table->float('amount_paid')->nullable();  
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('status_id')->nullable(); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('accountreceivable_id')->references('id')->on('accountreceivables'); 
        $table->foreign('created_by')->references('id')->on('users'); 
        $table->foreign('status_id')->references('id')->on('status');  
        $table->timestamps();
    });


   
    Schema::create('attachmentsreceivable', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('accountreceivable_id'); 
        $table->string('notes')->nullable();
        $table->string('url')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->foreign('accountreceivable_id')->references('id')->on('accountreceivables'); 
        $table->timestamps();
    });
 
    Schema::create('consumptiontypes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->string('title')->nullable();
        $table->float('conversion_factor')->nullable(); 
        $table->float('minimum_rate')->nullable();         
        $table->unsignedBigInteger('created_by');
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
        $table->timestamps();
    });


    Schema::create('consumptions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id'); 
        $table->unsignedBigInteger('unit_id');
        $table->unsignedBigInteger('consumptiontype_id');
        $table->unsignedTinyInteger('month')->nullable(); 
        $table->year('year')->nullable(); 
        $table->unsignedInteger('previous_reading')->nullable();
        $table->unsignedInteger('current_reading')->nullable(); 
        $table->datetime('date_reading')->nullable(); 
        $table->unsignedBigInteger('created_by');
        $table->unique(['month','year']); 
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('unit_id')->references('id')->on('units');
        $table->foreign('consumptiontype_id')->references('id')->on('consumptiontypes');
        $table->foreign('created_by')->references('id')->on('users');  
       
        $table->timestamps();
    });

    Schema::create('walls', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->string('title');
        $table->string('body')->nullable(); 
        $table->date('expiration_date')->nullable();      
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
        $table->string('title',50); 
        $table->string('description'); 
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
        $table->string('photo');
        $table->string('description');
        $table->string('status')->default('lost'); //Found
        $table->string('solution')->default('em andamento'); 
        $table->date('solution_date')->nullable(); 
        $table->unsignedBigInteger('created_by');             
        $table->foreign('company_id')->references('id')->on('companies');
        $table->foreign('created_by')->references('id')->on('users');  
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
        Schema::dropIfExists('lostfounds'); 
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('docs'); 
        Schema::dropIfExists('walllikes');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('consumptions');
        Schema::dropIfExists('consumptiontypes');
        Schema::dropIfExists('attachmentreceivables');
        Schema::dropIfExists('itemaccountreceivables');
        Schema::dropIfExists('accountreceivables');
        Schema::dropIfExists('attachmentspayable');
        Schema::dropIfExists('itemaccountpayables');
        Schema::dropIfExists('accountpayables');
        Schema::dropIfExists('documenttypes'); 
        Schema::dropIfExists('prorates');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('areadisabledays');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('pollcorrectanswers');
        Schema::dropIfExists('pollanswers');
        Schema::dropIfExists('pollalternatives');
        Schema::dropIfExists('pollitems');
        Schema::dropIfExists('polls');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('stakeholderstatus');
        Schema::dropIfExists('unitpets');
        Schema::dropIfExists('unitvehicles');
        Schema::dropIfExists('unitrelatives');
        Schema::dropIfExists('unittenants');
        Schema::dropIfExists('tenanttypes');
        Schema::dropIfExists('units');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('relatives');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('genders');
        Schema::dropIfExists('maritalstatus');
        Schema::dropIfExists('bankaccounts');
        Schema::dropIfExists('financialaccounts');
        Schema::dropIfExists('status');
        Schema::dropIfExists('users');
        Schema::dropIfExists('companyusers');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('bankagencies');
        Schema::dropIfExists('banks');             
    }
}


/*

    bankagencies
companyusers
financialaccounts
bankaccounts
maritalstatus
maritalstatus
unittenants
stakeholderstatus
pollquestions
pollalternatives
pollanswers
pollcorrectanswers
documenttypes//New 
accountpayables
itemaccountpayables
attachmentpayables
accountreceivables
itemaccountreceivables
attachmentsreceivable
consumptiontypes
*/


