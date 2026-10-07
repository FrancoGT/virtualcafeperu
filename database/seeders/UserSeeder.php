<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear permisos
        Permission::create(['name' => 'manage categories']);
        Permission::create(['name' => 'manage subcategories']);
        Permission::create(['name' => 'manage products']);
        Permission::create(['name' => 'manage orders']);

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['manage categories', 'manage subcategories', 'manage products', 'manage orders']);

        $clientRole = Role::create(['name' => 'client']);
        // Agregar permisos adicionales al rol de usuario si es necesario

        // Crear usuarios asignando el rol correspondiente
        $user1 = User::create([
            'name' => 'Valeria Valdivia',
            'email' => '71058662@ucsm.edu.pe',
            'password' => bcrypt('12345678'),
        ]);
        $user1->assignRole('admin');

        $user2 = User::create([
            'name' => 'Anónimo',
            'email' => 'tamayoedgard067@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $user2->assignRole('client');

        
    }
}
