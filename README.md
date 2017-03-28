## Laravel Monolog MySQL Handler.

MySQL driver for Laravel Monolog.

### Installation

- [Packagist](https://packagist.org/packages/lichv/monolog-mysql)
- [GitHub](https://github.com/lichv/monolog-mysql)

To get the lastest version of Theme simply require it in your `composer.json` file.

~~~
"lichv/monolog-mysql": "dev-master"
~~~

You'll then need to run `composer install` to download it and have the autoloader updated.

Open up `config/app.php` and find the `providers` key.

~~~
'providers' => array(
    // ...
    'Logger\Laravel\Provider\MonologMysqlHandlerServiceProvider'
);
~~~

Publish config using artisan CLI.

~~~
php artisan config:publish lichv/monolog-mysql
~~~

Migrate tables.

~~~
php artisan migrate
~~~

## Usage

~~~php
Log::getMonolog()->pushHandler(new Logger\Monolog\Handler\MysqlHandler());
~~~

Or in `bootstrap/app.php`:

~~~php
$app->configureMonologUsing(function($monolog) use($app) {
    $monolog->pushHandler(new Logger\Monolog\Handler\MysqlHandler());
});
~~~

## Credits

Based on:

- [Pedro Fornaza] (https://github.com/lichv/monolog-mysql)
