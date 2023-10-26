<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '03015262920',
            'password' => bcrypt('12345678'), // Password should be enclosed in quotes
            'image' => 'abc', // No extra characters here
        ]);
    }
}
