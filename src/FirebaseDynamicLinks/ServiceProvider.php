<?php namespace AgelxNash\FirebaseDynamicLinks;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Container\Container as Application;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @var bool $defer Indicates if loading of the provider is deferred.
     */
    protected $defer = false;

    const CONFIG_SOURCE_PATH = '/../config/package.php';
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . self::CONFIG_SOURCE_PATH => config_path('firebase-dynamic-links.php')
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . self::CONFIG_SOURCE_PATH,
            'firebase-dynamic-links'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() : void
    {
        $this->registerClass($this->app);

        $this->registerCommand();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['FirebaseDynamicLinks'];
    }

    /**
     * @param \Illuminate\Contracts\Container\Container $app
     * @return void
     */
    protected function registerClass(Application $app) : void
    {
        $app->singleton('FirebaseDynamicLinks', function () {
            $config = app('config')->get('firebase-dynamic-links');
            return new FirebaseDynamicLinks($config);
        });

        $app->alias('FirebaseDynamicLinks', FirebaseDynamicLinks::class);
    }

    /**
     * Register the "mypackage:command" console command.
     */
    protected function registerCommand() : void
    {
        $this->app->bind('FirebaseDynamicLinks.command', Command::class);
        $this->commands('FirebaseDynamicLinks.command');
    }
}
