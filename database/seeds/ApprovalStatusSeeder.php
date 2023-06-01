<?php

use Illuminate\Database\Seeder;

class ApprovalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ApprovalStatus::insert([
            [
                'transaction_id' => 15,
                'approver_id' => 2, // Jundrie Tano
                'alternate_approver_id' => NULL,
                'sequence_number' => 0,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 15,
                'approver_id' => 3, // Regiland Regalado
                'alternate_approver_id' => NULL,
                'sequence_number' => 1,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 15,
                'approver_id' => 4, // Armand Dy
                'alternate_approver_id' => NULL,
                'sequence_number' => 2,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 15,
                'approver_id' => 5, // Raul Villanueva
                'alternate_approver_id' => NULL,
                'sequence_number' => 3,
                'status' => 'PENDING',
                'remarks' => NULL
            ],

            [
                'transaction_id' => 14,
                'approver_id' => 2, // Jundrie Tano
                'alternate_approver_id' => NULL,
                'sequence_number' => 0,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 14,
                'approver_id' => 3, // Regiland Regalado
                'alternate_approver_id' => NULL,
                'sequence_number' => 1,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 14,
                'approver_id' => 4, // Armand Dy
                'alternate_approver_id' => NULL,
                'sequence_number' => 2,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 14,
                'approver_id' => 5, // Raul Villanueva
                'alternate_approver_id' => NULL,
                'sequence_number' => 3,
                'status' => 'PENDING',
                'remarks' => NULL
            ],

            [
                'transaction_id' => 13,
                'approver_id' => 2, // Jundrie Tano
                'alternate_approver_id' => NULL,
                'sequence_number' => 0,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 13,
                'approver_id' => 3, // Regiland Regalado
                'alternate_approver_id' => NULL,
                'sequence_number' => 1,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 13,
                'approver_id' => 4, // Armand Dy
                'alternate_approver_id' => NULL,
                'sequence_number' => 2,
                'status' => 'PENDING',
                'remarks' => NULL
            ],
            [
                'transaction_id' => 13,
                'approver_id' => 5, // Raul Villanueva
                'alternate_approver_id' => NULL,
                'sequence_number' => 3,
                'status' => 'PENDING',
                'remarks' => NULL
            ]
        ]);
    }
}
