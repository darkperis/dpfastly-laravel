## Laravel Fastly

This package allows you to use Fastly together with Laravel.

It provides one facade:

- Fastly facade to handle purging

## Installation

Require this package using composer.

```
composer require darkperis/dpfastly-laravel
```

Laravel uses Auto-Discovery, so you won't have to make any changes to your application, the two middlewares and facade will be available right from the beginning.

#### Steps for Laravel >=5.1 and <=5.4

The package can be used for Laravel 5.1 to 5.4 as well, however due to lack of Auto-Discovery, a few additional steps have to be performed.

In `config/app.php` you have to add the following code in your `aliases`:

```
'aliases' => [
    ...
    'Fastly'   => Darkpony\Fastly\Fastly::class,
],
```

Copy `fastly.php` to `config/`:

Copy the package `config/fastly.php` file to your `config/` directory.

**important**: Do not add the ServiceProvider under `providers` in `config/app.php`.

#### Steps for Laravel 5.5 and above

You should publish the package configuration, which allows you to set the defaults for the `Cache-Control` header:

```
php artisan vendor:publish --provider="Darkpony\Fastly\FastlyServiceProvider"
```

## Usage

The package comes with 2 functionalities: Setting the cache control headers for Fastly and purging.

### cache-control

You'll be able to configure defaults in the `config/fastly.php` file, here you can set the max-age (`default_ttl`), the cacheability (`default_cacheability`) such as public, private or no-cache or enable esi (`esi`) in the `Cache-Control` response header.

If the `default_ttl` is set to `0`, then we won't return the `Cache-Control` response header.

You can control the config settings in your `.env` file as such:

- `FASTLY_API_KEY` - Specify the API Token for your Service at the Edgeport Platform
- `FASTLY_ENDPOINT` - accepts endpoint
- `FASTLY_ESI_ENABLED` - accepts `true` or `false` to whether you want ESI enabled or not globally; Default `false`
- `FASTLY_DEFAULT_TTL` - accepts an integer, this value is in seconds; Default: `0`
- `FASTLY_DEFAULT_CACHEABILITY` - accepts a string, you can use values such as `private`, `no-cache`, `public` or `no-vary`; Default: `no-cache`
- `FASTLY_GUEST_ONLY` - accepts `true` or `false` to decide if the cache should be enabled for guests only; Defaults to `false`

You set the cache-control header for fastly using a middleware, so we can in our routes do something like this:

```php
Route::get('/', function() {
    return view('frontpage');
})->middleware('cache.headers:public;max_age=2628000;etag');

```

### purge

If we have an admin interface that controls for example a blog, when you publish a new article, you might want to purge the frontpage of the blog so the article appears in the overview.

You'd do this in your controller by doing

```php
<?php

namespace App\Http\Controllers;

use Fastly;

class BlogController extends BaseController
{
    // Your article logic here

    Fastly::purge('/');
}
```

You can also purge everything by doing:

```php
Fastly::purge('*');
// or
Fastly::purgeAll();
```

One or multiple URIs can be purged by using a comma-separated list:

```php
Fastly::purge('/blog,/about-us,/');
````

### Laravel Authentication

If you use authentication in Laravel for handling guests and logged-in users, you'll likely want to also separate the cache for people based on this.

This can be done in the `.htaccess` file simply by using the cache-vary on the Authorization cookie:

```apache
RewriteEngine On
RewriteRule .* - [E=Cache-Vary:Authorization]
```

**Note: In the above example we use `Authorization`, this may have a different name depending on your setup, so it has to be changed accordingly.**
