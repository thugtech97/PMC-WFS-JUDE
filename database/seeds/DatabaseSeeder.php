<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
    * Seed the application's database.
    *
    * @return void
    */
    public function run()
    {
        $this->call([

            TransactionSeeder::class,
            TemplateSeeder::class,
            TemplateApproverSeeder::class,
            ApprovalStatusSeeder::class
        ]);

        $this->users();
        $this->applications();
    }

    public function users()
    {
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email' => 'admin@philsaga.com',
                'department' => 'Information Communications Technology',
                'dept_id' => 1,
                'designation' => 'admin user',
                'role' => 'admin',
                'isActive' => 1,
                'user_type' => 'ict',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Jundrie Tano',
                'username' => 'jatano',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email' => 'jatano@philsaga.com',
                'department' => 'Information Communications Technology',
                'dept_id' => 1,
                'designation' => 'supervisor',
                'role' => 'read write',
                'isActive' => 1,
                'user_type' => 'approver',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Regiland Regalado',
                'username' => 'rregalado',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email' => 'rregalado@philsaga.com',
                'department' => 'Information Communications Technology',
                'dept_id' => 1,
                'designation' => 'ict manager',
                'role' => 'read write',
                'isActive' => 1,
                'user_type' => 'approver',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Armand Dy',
                'username' => 'ady',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email' => 'ady@philsaga.com',
                'department' => 'Accounting',
                'dept_id' => 1,
                'designation' => 'accounting manager',
                'role' => 'read write',
                'isActive' => 1,
                'user_type' => 'approver',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Raul Villanueva',
                'username' => 'rvillanueva',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email' => 'rvillanueva@philsaga.com',
                'department' => 'Executive',
                'dept_id' => 1,
                'designation' => 'president',
                'role' => 'read write',
                'isActive' => 1,
                'user_type' => 'approver',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'name' => 'Jessa Gallardo',
                'username' => 'jgallardo',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', //password
                'email' => 'jgallardo@philsaga.com',
                'department' => 'Information Communications Technology',
                'dept_id' => 1,
                'designation' => 'secretary',
                'role' => 'read write',
                'isActive' => 1,
                'user_type' => 'user',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]
        ];

        DB::table('users')->insert($users);
    }

    public function applications()
    {
        $apps = [
            [
                'name' => 'OREM - Travel Order',
                'token' =>  'base64:Ql3H5Ng0whuQGF64YaQn6RiagxcIrINasHt4bLu3333=',
                'template_id' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('allowed_transactions')->insert($apps);
    }
}
