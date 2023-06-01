<?php

use Illuminate\Database\Seeder;

class TemplateApproverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\TemplateApprover::insert([
            [
                'template_id' => 1,
                'approver_id' => 2, // Jundrie Tano
                'alternate_approver_id' => NULL,
                'sequence_number' => 0
            ],
            [
                'template_id' => 1,
                'approver_id' => 3, // Regiland Regalado
                'alternate_approver_id' => NULL,
                'sequence_number' => 1
            ],
            [
                'template_id' => 1,
                'approver_id' => 4, // Armand Dy
                'alternate_approver_id' => NULL,
                'sequence_number' => 2
            ],
            [
                'template_id' => 1,
                'approver_id' => 5, // Raul Villanueva
                'alternate_approver_id' => NULL,
                'sequence_number' => 3
            ],
        ]);
    }
}
