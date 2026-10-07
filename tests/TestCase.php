<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    // Deshabilita el refresco automático de la base de datos
    protected $refreshDatabase = false;
    use CreatesApplication;
}
