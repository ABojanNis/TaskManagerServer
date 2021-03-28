<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Helpers\RoleTypes::all() as $role) {
            Role::create(['name' => $role]);
        }
    }
}
