# Slim 3 Framework Mustache View

[![Build Status](https://travis-ci.org/andrewslince/slim3-mustache-view.svg?branch=master)](https://travis-ci.org/andrewslince/slim3-mustache-view)
[![Latest Stable Version](https://poser.pugx.org/andrewslince/slim3-mustache-view/version)](https://packagist.org/packages/andrewslince/slim3-mustache-view)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andrewslince/slim3-mustache-view/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andrewslince/slim3-mustache-view/?branch=master)
[![License](https://poser.pugx.org/andrewslince/slim3-mustache-view/license)](https://packagist.org/packages/andrewslince/slim3-mustache-view)

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

## Testing

To running unit tests, executes the command below:

```bash
./vendor/bin/phpunit -c phpunit.xml.dist
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Andrews Lince](https://github.com/andrewslince)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
