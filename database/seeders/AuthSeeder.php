<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin Kasir',
            'email' => 'adminkasir@mail.com',
            'password' => Hash::make('adminkasir'),
            'role' => 'admin_kasir'
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@mail.com',
            'password' => Hash::make('kasir'),
            'role' => 'kasir'
        ]);
    }
}
