<?php

namespace EduardaLeal\CleanFlow\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use EduardaLeal\CleanFlow\Providers\ArchitectureServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ArchitectureServiceProvider::class,
        ];
    }
}
