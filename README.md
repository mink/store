# Store
Store is an extension of Laravel's [Collection](https://laravel.com/docs/7.x/collections) that allows you to create and access your data however you like.

With Store, you can provide Collection's toolset to the entire scope of an existing set of data or create a new data set from the ground up, which you are able to get and assign items in both the context of a Collection object or an array.

When accessing data within a `StoreCollection`, this:
```php
$user->name;
```
is the equivalent of:
```php
$user['name'];
```
No matter how nested into the data structure you are, you will always have access to all of Collection's existing methods. You will also be able to access the upper hierarchy of the data by using the new `parent()` method.
```php
$user->traits = ['frugal', 'kleptomaniac'];
$user->traits->first(); // 'frugal'
$user['traits']->parent(); // goes back up to $user
```

You can also use dot syntax in Collection's `get()` method as a shortcut to accessing nested items.
```php
$config->site->settings->maintenance = true;
$config->get('site.settings.maintenance'); // bool(true)
```

## Installation
You can install Store using Composer.

Store requires PHP >=7.4 and Laravel >=7.0 if you are using Laravel.
```
composer require x/store
```
Anywhere you normally use a Collection instance you are able to use `StoreCollection` instead. you can also use the `store()` helper function to create an instance.
```php
$array = ['testing' => 123];
$store = store($array); // or new X\Store\StoreCollection($array)
```