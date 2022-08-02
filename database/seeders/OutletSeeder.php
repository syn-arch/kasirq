<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Outlet::create([
            'name' => 'Toko 1',
            'address' => 'Jl. Raya No. 1',
            'phone' => '08123456789',
            'email' => 'toko1@mail.com'
        ]);
    }
}
