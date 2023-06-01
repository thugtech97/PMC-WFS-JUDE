<?php

use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Template::insert([
            [
                'name' => 'Standard Approval',
                'user_id' => 1,
                'created_at' => '2020-04-12 20:31:26',
                'updated_at' => '2020-04-12 20:31:26'
            ],
        ]);
    }
}
