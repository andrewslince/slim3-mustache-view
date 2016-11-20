# Slim 3 Framework Mustache View

A [PHP Mustache](https://github.com/bobthecow/mustache.php) class for render views on [Slim 3 Framework](http://www.slimframework.com/).

## Requirements

* [Slim](http://www.slimframework.com/) 3.0.0 or newer;
* [PHP](http://www.php.net/) 5.6 or newer.

## Install

Via [Composer](https://getcomposer.org/):

``` bash
$ composer require andrewslince/slim3-mustache-view
```

## Usage

```php
<?php

$app = new \Slim\App([
    // your application settings
]);

// get application container
$container = $app->getContainer();

// register view template engine and configurations
$container['renderer'] = function () {
    return new \Slim\Views\Mustache([

        // REQUIRED
        'template' => [

            // REQUIRED
            'paths' => [
                realpath('./templates')
            ],

            // optional
            'extension' => 'html',

            // optional
            'charset' => 'utf-8',
        ],

        // put other mustache options here¹
    ]);
};

// use the render() method in your application routes
$app->get('/', function ($request, $response, $args) {

    // render your view
    return $this->renderer->render(
        $response,
        'index',
        $args
    );
});

$app->run();
```

**NOTES:**

¹ See other Mustache options [here](https://github.com/bobthecow/mustache.php/wiki#constructor-options).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Andrews Lince](https://github.com/andrewslince)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
