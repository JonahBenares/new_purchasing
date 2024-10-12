<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void

    {
        $users = [
            [
                'name' => 'Mila Arana',
                'email' => 'milaarana@gmail.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Syndy Sinoro',
                'email' => 'syndy@gmail.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Maylen Cabaylo',
                'email' => 'maylen@gmail.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Prency Francisco',
                'email' => 'prency@gmail.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => bcrypt('password123'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
