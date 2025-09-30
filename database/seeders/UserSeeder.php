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
            'name' => 'AdminTlahDatang',
            'email' => 'blabla@gmail.com',
            // biarkan password kosong dulu, nanti kamu isi manual / hash sendiri
            'password' => 'adhdadhd',
            'role' => 'admin',
        ]);
    }
}
