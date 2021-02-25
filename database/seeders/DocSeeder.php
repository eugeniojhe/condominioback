<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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


    }
}
