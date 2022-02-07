<?php

use Illuminate\Database\Seeder;


class IntegradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            LicenciadoSeeder::class,
        ]);
    }
}
