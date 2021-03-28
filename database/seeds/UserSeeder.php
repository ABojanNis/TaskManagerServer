
<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@admin.com',
            'password' => 123456,
        ]);

        $user->assignRole(\App\Helpers\RoleTypes::ADMIN);
    }
}
