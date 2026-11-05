<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Roles básicos. users.role_id es una clave foránea a roles (por defecto 1 = admin),
 * así que deben existir antes de crear usuarios. Se puede ejecutar varias veces.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('admin');
        Role::findOrCreate('client');
    }
}
