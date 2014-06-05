<?php namespace Tdt\Multisite;

use Illuminate\Support\ServiceProvider;

use Tdt\Multisite\Commands\AddSite;

class MultisiteServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->package('tdt/multisite');

        $this->app['multisite.add'] = $this->app->share(function ($app) {
            return new AddSite();
        });

        $this->commands('multisite.add');

        include __DIR__ . '/../../routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
