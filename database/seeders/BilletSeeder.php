<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BilletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
