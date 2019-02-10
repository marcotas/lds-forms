<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            TeamsTableSeeder::class,
            RolesTableSeeder::class,
            // PermissionsTableSeeder::class,
            ClientsTableSeeder::class,
            ServicesTableSeeder::class,
        ]);
    }
}
