<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
