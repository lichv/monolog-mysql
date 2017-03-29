<?php
namespace Logger\Laravel\Provider;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class MonologMysqlHandlerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(){
        $path = realpath(__DIR__ . '/../migrations');
        $this->publishes([
            $path => database_path('migrations')
        ]);
        $migratePath = substr($path,strlen(base_path())+1);
        Artisan::call('migrate',['--path'=>$migratePath]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(){
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(){
        return array();
    }
}
