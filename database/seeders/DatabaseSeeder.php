<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();



        DB::table('banks')->insert([
            'bank' => 104,
            'name' => 'Caixa Economica',
        ]);
        DB::table('bankagencies')->insert([
           'bank_id' => 1, 
            'bank' => 104, 
            'agency' =>162,
            'name' => 'Praça Tubal Vilela',

        ]);
        DB::table('companies')->insert([
            'name' => 'Condominio Santos Dumont',
            'trading' => 'Uberlandia Dumonto',
            'email' => 'dumont@gmail.com',
            'foundation_date' => '2021-02-21',
            'address' => 'Rua Wilson Batista',
            'address_number' => 83,
            'city' => 'Uberlândia',
            'state' => 'MG',
            'tax_types' => 'monthly',
            'fee_types' => 'percentage',
            'charge_type' => 'prorated',
            'condominium_value' => 300.00,
            'has_block' => true, 
            'bank_slip' => 1,
            'bankagency_slip' => 1,
            'created_by' => 1, 
        ]);

        
        DB::table('users')->insert([
         'company_id' => 1,
         'name' => 'eugenio', 
         'cpf'  => 35178388615,
         'email' => 'eugenio@gmail.com',
         'password' => password_hash('123456',PASSWORD_DEFAULT),        
      ]);


     DB::table('companyusers')->insert([
      'company_id' => 1,
      'user_id' => 1, 
      
  ]);

        DB::table('status')->insert([
            'company_id' => 1,
            'description' => 'Ativo', 
            'created_by' => 1,
        ]);
        DB::table('status')->insert([
            'company_id' => 1,
            'description' => 'Cancelado', 
            'created_by' => 1,
        ]);
        DB::table('status')->insert([
            'company_id' => 1,
            'description' => 'Suspenso', 
            'created_by' => 1,
        ]);
        DB::table('status')->insert([
            'company_id' => 1,
            'description' => 'Em Analise', 
            'created_by' => 1,
        ]);

        DB::table('financialaccounts')->insert([
            'company_id' => 1,
            'type' => 1,
            'name' => 'Caixa Bancos',
            'created_by' => 1,
            'status_id' => 1
        ]);
        DB::table('financialaccounts')->insert([
            'company_id' => 1,
            'type' => 2,
            'name' => 'Pagamento de combustivel',
            'created_by' => 1,
            'status_id' => 1
        ]);

        DB::table('bankaccounts')->insert([
            'company_id' => 1,
            'bank_id' => 1,
            'bankagency_id' => 1,
            'account_number' => 23447,
            'account_digit'  => 3,
            'account_holder' => 'Condominio Santos Dumont',
            'type' => 1,
            'created_by' => 1,
            'status_id' => 1,           
        ]);

        DB::table('maritalstatus')->insert([
            'name' => 'Solteiro',
            'created_by' => 1,

        ]);

        DB::table('genders')->insert([
            'name' => 'Masculino',
            'created_by' => 1,            
        ]);
        DB::table('genders')->insert([
            'name' => 'Femenino',
            'created_by' => 1,            
        ]);

        DB::table('tenants')->insert([
           'company_id' => 1,
           'name' => 'Joao dos Reis Veloso',
           'email' => 'joaoveloso@gmail.com',
           'user_id' => 1, //Tenant as user; 
           'gender_id' => 1,
           'created_by' => 1, 
        ]);
        DB::table('tenants')->insert([
            'company_id' => 1,
            'name' => 'Vinicus Eugeni0',
            'email' => 'vinicius@gmail.com',
            'user_id' => 1,//Tenant as a user 
            'gender_id' => 1,
            'created_by' => 1, 
         ]);

         DB::table('tenants')->insert([
            'company_id' => 1,
            'name' => 'Ana Julia',
            'email' => 'anajulia@gmail.com',
            'user_id' => 1,//Tenant as a user 
            'gender_id' => 1,
            'created_by' => 1, 
         ]);

        DB::table('contacts')->insert([
            'company_id' => 1,
            'name' => 'Paulinho da viola',
            'email' => 'paulinho@gmail.com',
             'created_by' => 1,
         ]);


         DB::table('blocks')->insert([
            'company_id' => 1,
            'name' => 01,
            'details' => 'Quadra 10',
            'area' => 1000.00,
            'created_by' => 1, 
         ]);

         DB::table('units')->insert([
            'company_id' => 1,
            'block_id' => 1,
            'name' => 'Apo 201',
            'area' => 1500,
            'ideal_fraction' => 5.00,
            'created_by' => 1
         ]);

         DB::table('units')->insert([
            'company_id' => 1,
            'block_id' => 1,
            'name' => 'Apo 301',
            'area' => 800,
            'ideal_fraction' => 5.00,
            'created_by' => 1
         ]);

         DB::table('units')->insert([
            'company_id' => 1,
            'block_id' => 1,
            'name' => 'Apo 401',
            'area' => 500,
            'ideal_fraction' => 5.00,
            'created_by' => 1
         ]);

         DB::table('tenanttypes')->insert([
            'company_id' => 1,
            'name' => 'Morador',           
            'created_by' => 1
         ]);
         DB::table('tenanttypes')->insert([
            'company_id' => 1,
            'name' => 'Dono',           
            'created_by' => 1
         ]);

         DB::table('unittenants')->insert([
            'unit_id'=> 1,
            'company_id' => 1,
            'tenant_id' => 1,
            'tenanttype_id' => 1,
            'entry_date' => '2021-02-22',                      
            'created_by' => 1
         ]);
         DB::table('unittenants')->insert([
            'unit_id'=> 2,
            'company_id' => 1,
            'tenant_id' => 1,
            'tenanttype_id' => 2,
            'entry_date' => '2021-02-22',                      
            'created_by' => 1
         ]);

         DB::table('unittenants')->insert([
            'unit_id'=> 3,
            'company_id' => 1,
            'tenant_id' => 1,
            'tenanttype_id' => 2,
            'entry_date' => '2021-02-22',                      
            'created_by' => 1
         ]);

         DB::table('unitvehicles')->insert([
            'unit_id'=> 1,
            'company_id' => 1,
            'name' => 'Celta', 
            'color' => '#FFFFFF',
            'plate' => 'ONX-3226',
            'year' => '2014',                   
            'created_by' => 1
         ]);

         DB::table('unitvehicles')->insert([
            'unit_id'=> 1,
            'company_id' => 1,
            'name' =>'Fiat', 
            'color' => '#000000',
            'plate' => 'KCH-1026',
            'year' => '2018',                   
            'created_by' => 1
         ]);

         DB::table('unitvehicles')->insert([
            'unit_id'=> 1,
            'company_id' => 1,
            'name' => 'Chevrolet', 
            'color' => '#000000',
            'plate' => 'KCH-1026',
            'year' => '2021',                   
            'created_by' => 1
         ]);

         DB::table('stakeholderstatus')->insert([
            'company_id' => 1,
            'title' => 'Ativo',                   
            'created_by' => 1
         ]);

         DB::table('profiles')->insert([
           'company_id' => 1,
            'title' => 'Presidente',                   
            'created_by' => 1
         ]);

         DB::table('profiles')->insert([
           
            'company_id' => 1,
            'title' => 'Gerente',                   
            'created_by' => 1
         ]);

         DB::table('profiles')->insert([
            'company_id' => 1,
            'title' => 'Desenvolvedor de sistemas',                   
            'created_by' => 1
         ]);


         DB::table('providers')->insert([
            'company_id' => 1,
            'name' => 'Companhia de agua e esgoto da cidade',  
            'stakeholderstatus_id' => 1,                  
            'created_by' => 1
         ]);

         DB::table('providers')->insert([
            'company_id' => 1,
            'name' => 'Comanhia eletrica de Minas Gerais', 
            'stakeholderstatus_id' => 1,                  
            'created_by' => 1
         ]);

         DB::table('customers')->insert([
            'company_id' => 1,
            'name' => 'Joao e Pedro ', 
            'stakeholderstatus_id' => 1,                  
            'created_by' => 1
         ]);

         DB::table('customers')->insert([
            'company_id' => 1,
            'name' => 'Manuel Danta de Araujo', 
            'stakeholderstatus_id' => 1,                    
            'created_by' => 1
         ]);

         DB::table('areas')->insert([
            'company_id' => 1,
            'unit_id' => '', 
            'title' => 'Piscina 01',
             'cover' => 'pool.jpg',  
            'days'  => '1,2,5,7', 
            'start_time' => '09:00:00',
            'end_time' => '21:00:00',
            'created_by' => 1
         ]);

         DB::table('areas')->insert([
            'company_id' => 1,
            'unit_id' => 1, 
            'title' => 'Churraqueira 02 ',                   
            'cover' => 'grill.jpg',  
            'days'  => '1,2,5,7', 
            'start_time' => '09:00:00',
            'end_time' => '21:00:00',
            'created_by' => 1
         ]);

         DB::table('areas')->insert([
            'company_id' => 1, 
            'unit_id' => 2, 
            'title' => 'Sauna 01',                   
            'cover' => 'sauna.jpg',  
            'days'  => '1,2,5,7', 
            'start_time' => '09:00:00',
            'end_time' => '21:00:00',
            'created_by' => 1
         ]);

         DB::table('walls')->insert([
            'company_id' => 1, 
            'title' => 'Reuniao',
            'body' => 'bla bla bla bla bla bla', 
            'expiration_date' => '2021-02-28', 
            'created_by' => 1
         ]);

         DB::table('walls')->insert([
            'company_id' => 1, 
            'title' => 'Prestação de contas',
            'body' => 'bla bla bla bla bla bla', 
            'expiration_date' => '2021-03-28', 
            'created_by' => 1
         ]);


         DB::table('walls')->insert([
            'company_id' => 1, 
            'title' => 'Seguranã de condominio',
            'body' => 'bla bla bla bla bla bla', 
            'expiration_date' => '2021-03-30', 
            'created_by' => 1
         ]);

         DB::table('walls')->insert([
            'company_id' => 1, 
            'title' => 'Festa de Fim de ano',
            'body' => 'bla bla bla bla bla bla', 
            'expiration_date' => '2021-12-30', 
            'created_by' => 1
         ]);



         DB::table('walllikes')->insert([
            'company_id' => 1, 
            'user_id'  => 1, 
            'wall_id' => 1,
          
         ]);

         DB::table('walllikes')->insert([
            'company_id' => 1, 
            'user_id'  => 1, 
            'wall_id' => 2,
           
         ]);

         DB::table('walllikes')->insert([
            'company_id' => 1, 
            'user_id'  => 1, 
            'wall_id' => 1,           
         ]);

         DB::table('walllikes')->insert([
            'company_id' => 1, 
            'user_id'  => 1, 
            'wall_id' => 2,           
         ]);

         DB::table('docs')->insert([
            'company_id' => 1, 
            'title' => 'Fatura Cemig',
            'url' => 'fatura_cemig_fevereiro_2021.pdf', 
            'user_id' => 1 
         ]);

         DB::table('docs')->insert([
            'company_id' => 1, 
            'title' => 'Fatura Ctbc',
            'url' => 'fatura_ctbc_fevereiro_2021.pdf', 
            'user_id' => 1,
         ]);

         DB::table('docs')->insert([
            'company_id' => 1, 
            'title' => 'Fatura Master Free',
            'url' => 'FATURA_MASTER_FREE.pdf', 
            'user_id' => 1,
         ]);

         DB::table('billets')->insert([
            'company_id' => 1, 
            'title' => 'Fatura Cemig - Billets',
            'url' => 'fatura_cemig_fevereiro_2021.pdf',
            'unit_id' => 3, 
            'created_by' => 1 
         ]);

         DB::table('billets')->insert([
            'company_id' => 1, 
            'title' => 'Fatura Ctbc Billets',
            'url' => 'fatura_ctbc_fevereiro_2021.pdf', 
            'unit_id' => 2,
            'created_by' => 1,
         ]);

         DB::table('billets')->insert([
            'company_id' => 1, 
            'title' => 'Fatura Master Free Billets',
            'url' => 'FATURA_MASTER_FREE.pdf', 
            'unit_id' => 3, 
            'created_by' => 1,
         ]);

    }
}
 