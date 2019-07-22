# Set PHP variables to a JavaScript global helper

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/blok/laravel-javascript/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/blok/laravel-javascript/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/blok/laravel-javascript/badges/build.png?b=master)](https://scrutinizer-ci.com/g/blok/laravel-javascript/build-status/master)
[![Packagist](https://poser.pugx.org/blok/laravel-javascript/d/total.svg)](https://packagist.org/packages/blok/laravel-javascript)
[![Packagist](https://img.shields.io/packagist/l/blok/laravel-javascript.svg)](https://packagist.org/packages/blok/laravel-javascript)

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
		"blok/javascript": "~1.0"
	}
}
```

### Laravel Users

If you are a Laravel user, there is a service provider you can make use of to automatically prepare the bindings and such.

```php

// config/app.php

'providers' => [
    '...',
    'Blok\JavaScript\JavaScriptServiceProvider'
];
```

When this provider is booted, you'll gain access to a helpful `JavaScript` facade, which you may use in your controllers.

```php
public function index()
{
    JavaScript::set([
        'foo' => 'bar',
        'user' => User::first(),
        'age' => 29
    ]);
    
    JavaScript::setNamespace('_labels')->set(Label::all());

    // ...
}
```

> In Laravel 5, of course add `use JavaScript;` to the top of your controller.

Then, you need to render the JavaScript. For example :

```
<body>
    <h1>My Page</h1>
    @javascript() // render in window.__app global 
    @javascript('_labels') // render in window._labels global
</body>
```
## License

[View the license](https://github.com/blok/laravel-javascript/blob/master/LICENSE) for this repo.


## TODO
- Allow to get/set a variable from the directive : ex. `javascript('key')` and `javascript('key', $value)`
- Allow to config the auto-rendering of the JavaScript (when not using directive)
- Create a specific directive to render the JavaScript (ex. `javascript()->render($namespace)`