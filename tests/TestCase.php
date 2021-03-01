<?php

namespace NovaListCard\CardServiceProvider\Tests;

use NovaListCard\CardServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            CardServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        /*
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        */

        /*
        include_once __DIR__.'/../database/migrations/create_nova_list_card_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
