<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class LicenciadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'Licenciado']);
        $role->givePermissionTo('pipeline-list');
        $role->givePermissionTo('pipeline-edit');
        $role->givePermissionTo('pipeline-create'); 
        $role->givePermissionTo('lista-pipeline'); 
        $role->givePermissionTo('novo-pipeline'); 
        $role->givePermissionTo('novo-cadastro'); 
        $role->givePermissionTo('lista-cadastro'); 
        $role->givePermissionTo('visualiza-cadastro'); 
        $role->givePermissionTo('visualiza-criativo'); 
        $role->givePermissionTo('visualiza-pipeline'); 
        $role->givePermissionTo('novo-criativo'); 
        $role->givePermissionTo('totem-list'); 
        $role->givePermissionTo('totem-edit'); 
        $role->givePermissionTo('lista-criativo'); 
        $role->givePermissionTo('criativo-list');
        $role->givePermissionTo('criativo-create'); 
        $role->givePermissionTo('criativo-edit'); 
        $role->givePermissionTo('clienteslicenciado-list'); 
        $role->givePermissionTo('clienteslicenciado-create'); 
        $role->givePermissionTo('clienteslicenciado-edit'); 
        $role->givePermissionTo('relatorio-sintetico'); 

    }
}
