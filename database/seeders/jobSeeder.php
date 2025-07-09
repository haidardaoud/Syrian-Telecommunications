<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     public function run()
     {
         DB::table('job_offers')->insert([
             ['salary' => '0',
            'position' => 'customer'],
         ]);
     }
}
