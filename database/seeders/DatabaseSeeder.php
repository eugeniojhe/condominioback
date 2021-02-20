<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use illuminate\Support\Facades\DB; 

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
            'bank' => 104, 
            'agency' =>162,
            'name' => 'Praça Tubal Vilela',

        ]);
        DB::table('companies')->insert([
            'name' => 'Condominio Santos Dumont',
            'tranding' => 'Uberlandia Dumonto',
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
            'bankagency_slip' => 1
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

        DB::table('tenantes')->insert([
           'company_id' => 1,
           'name' => 'Joao dos Reis Veloso',
           'email' => 'joaoveloso@gmail.com',
           'user_id' => 1,
           'gender_id' => 1,
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
            'ared' => 1,000.00,
         ]);

         DB::table('units')->insert([
            'company_id' => 1,
            'block_id' => 1,
            'name' => 'Apo 201',
            'area' => 170,

         ]);

    }
}
 