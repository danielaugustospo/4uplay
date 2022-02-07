<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',

           'usuarios-list',
           'usuarios-create',
           'usuarios-edit',
           'usuarios-delete',
        
           'product-list',
           'product-create',
           'product-edit',
           'product-delete',
        
        
           'relatorio-financeiro',
           'relatorio-sintetico',

           'novo-cadastro',
           'lista-cadastro',
           'visualiza-cadastro',
           'exclui-cadastro',

           'novo-pipeline',
           'lista-pipeline',
           'visualiza-pipeline',
           'exclui-pipeline',

           'novo-criativo',
           'lista-criativo',
           'visualiza-criativo',
           'exclui-criativo',

        'pipeline-list',
        'pipeline-create',
        'pipeline-edit',
        'pipeline-delete',
        'pipeline-historico',

        'criativo-list',
        'criativo-create',
        'criativo-edit',
        'criativo-delete',
        'criativo-historico',

        'mensalidade-list',
        'mensalidade-create',
        'mensalidade-edit',
        'mensalidade-delete',
        'mensalidade-historico',

        'totem-list',
        'totem-create',
        'totem-edit',
        'totem-delete',
        'totem-historico',

        'clienteslicenciado-list',
        'clienteslicenciado-create',
        'clienteslicenciado-edit',
        'clienteslicenciado-delete',
        'clienteslicenciado-historico',

        ];


        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
