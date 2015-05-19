[![Build Status](https://travis-ci.org/creitive/laravel4-breadcrumbs.png)](https://travis-ci.org/creitive/laravel4-breadcrumbs) [![Latest Stable Version](https://poser.pugx.org/creitive/laravel4-breadcrumbs/version.png)](https://packagist.org/packages/creitive/laravel4-breadcrumbs) [![Total Downloads](https://poser.pugx.org/creitive/laravel4-breadcrumbs/d/total.png)](https://packagist.org/packages/creitive/laravel4-breadcrumbs)

Laravel 4 Breadcrumbs
=====================

*A simple Laravel 4-compatible breadcrumbs package. Generates Twitter Bootstrap-compatible output.*


Installation
------------

Just run this on the command line:

```
composer require creitive/laravel4-breadcrumbs
```

After this, you should add the service provider and the alias to your `app/config/app.php`, which should make it look something like this:

```php
return array(
	// ...

	'providers' => array(
		// ...

		'Creitive\Breadcrumbs\BreadcrumbsServiceProvider',
	),

	// ...

	'aliases' => array(
		// ...

		'Breadcrumbs' => 'Creitive\Breadcrumbs\Facades\Breadcrumbs',
	),
);
```

You're all set!


Usage
-----

For usage documentation, please visit the core library that this package depends on: [creitive/breadcrumbs](https://github.com/creitive/breadcrumbs).

This particular package registers a shared instance of the breadcrumbs class, and enables you to make the calls on the provided facade, ie. instead of doing `$breadcrumbs->addCrumb('Home', '/')`, you can do `\Breadcrumbs::addCrumb('Home', '/')`.

Additionally, having this as a separate package enables us to move forward with Laravel-specific features, such as having a configuration file that enables you to more cleanly customize how the package works.


Laravel 5
---------

For Laravel 5 support, a separate package will soon be available, and it will be linked from this repo as soon as it's online.


License
-------

The code is licensed under the MIT license, which is available in the `LICENSE` file.
