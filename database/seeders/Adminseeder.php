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
        $admin = Admin::firstOrNew(['email' => 'admin@gmail.com']);

        // Update the admin details
        $admin->fill([
            'name' => 'admin',
            'phone' => '0123456789',
            'password' => bcrypt(12345678),
            'image' => 'public/admin/assets/images/users/admin.png',
        ]);
        $admin->save();
    }
}
