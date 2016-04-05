<?php

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $app;

    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->register('\ShoppingCart\Providers\ShoppingCartServiceProvider');

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $config = require __DIR__.'/../resources/config/shopping-cart.php';

        $app->config->set('shopping-cart', $config);

        $this->app = $app;

        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

}
