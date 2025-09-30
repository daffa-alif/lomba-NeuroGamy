<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'daffa',
            'email' => 'daffa@gmail.com',
            'password' => 'Fullammo9', 
            'role' => 'admin',
        ],
        User::create([
            'name' => '',
            'email' => 'daffa@gmail.com',
            'password' => 'Fullammo9', 
            'role' => 'admin',
        ],
        
    );

    }
}
