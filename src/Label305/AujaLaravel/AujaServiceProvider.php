<?php namespace Label305\AujaLaravel;

use Illuminate\Support\ServiceProvider;
use Label305\AujaLaravel\Config\AujaConfigurator;
use Label305\AujaLaravel\Database\MySQLDatabaseHelper;
use Label305\AujaLaravel\Exceptions\NoDatabaseHelperException;
use Label305\AujaLaravel\Routing\AujaRouter;

/**
 * The Laravel service provider
 * Include this class in your laravel app/config/app.php file to load it at bootstrap.
 *
 * @author  Thijs Scheepers - <thijs@label305.com>
 *
 * @package Label305\AujaLaravel
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
class AujaServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        $this->registerConfigurator();

        $this->registerManager();

        $this->registerRouter();
    }

    /**
     * Register the manager.
     */
    protected function registerManager()
    {
        $this->app->singleton('auja', function ($app) {
            $config = $app['config']['auja-laravel'] ?: $app['config']['auja-laravel::config'];
            return new Auja($app, $app['auja.configurator'], $config['models']);
        });

        $this->app->bind('Label305\AujaLaravel\Auja', 'auja');
    }

    /**
     * Register the auja configurator
     */
    protected function registerConfigurator()
    {
        $this->app->singleton('auja.database', function($app) {

            $config = $app['config']['auja-laravel'] ?: $app['config']['auja-laravel::config'];

            switch ($config['database']) {
                case 'mysql':
                    return new MySQLDatabaseHelper();
                    break;
                default:
                    throw new NoDatabaseHelperException('No Auja database helper for ' . $config['database']);
                    break;
            }
        });

        $this->app->bind('Label305\AujaLaravel\Database\DatabaseHelper', 'auja.database');

        $this->app->singleton('auja.configurator', function($app) {
            return new AujaConfigurator($app, $app['auja.database']);
        });

        $this->app->bind('Label305\AujaLaravel\Config\AujaConfigurator', 'auja.configurator');
    }

    /**
     * Register the router
     */
    protected function registerRouter()
    {
        $this->app->singleton('auja.router', function($app) {

            $config = $app['config']['auja-laravel'] ?: $app['config']['auja-laravel::config'];

            return new AujaRouter($app['auja'], $app['router'], $config['route']);
        });

        $this->app->bind('Label305\AujaLaravel\Routing\AujaRouter', 'auja.router');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['auja', 'auja.router', 'auja.database', 'auja.configurator'];
    }

    /**
     * Boot the package
     */
    public function boot() {

        $this->package('label305/auja-laravel');

        $app = $this->app;

        // Include the routes file located in src/routes.php of this package
        include __DIR__.'/../../routes.php';
    }


}
