
Laravel Http Logger
==============

Laravel Http Logger is a package that help you to log and see incoming requests to you server and outgoing. 

It includes values:
* Request method
* Get/Post request variables
* Response data
* Headers
* Cookies
* Process time (ms)
* Current user id (if exists)
* Datetime of request

This package also logs all outgoing requests you can make with Laravel HTTP Client. 
It includes the same values as for incoming requests (Request method, Get/Post request variables, ets... )

## Instalation guide

### Install the package via composer
```
composer require hutsoliak/laravel-http-logger
```


### Migrations

You need to run migration for `logs_http` table

```
php artisan migrate
```

### Add variable to config/services.php config

The package works only if you enable it in config.
You can exclude some urls you don't want to log

```
return [
    ...
    'http_logger' => [
        'enabled' => true,
        'ignoreUrls' => [
            '^/admin/.+$', // regexp
            '^/admin/login',
        ],
    ]
];
```

## Contributing

Thank you for considering contributing to Laravel Http Logger! You can read the contribution guide [here](CONTRIBUTING.md).

## License

Laravel Http Logger is open-sourced software licensed under the [MIT license](LICENSE).

