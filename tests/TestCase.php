<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Tras migrar (RefreshDatabase) se crean los roles: users.role_id es una
     * clave foránea a roles con valor por defecto 1.
     */
    protected $seed = true;

    protected $seeder = RoleSeeder::class;
}
