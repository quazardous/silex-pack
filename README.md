# silex-pack
Add bundle like system to Silex 2.x

There is a [demo project](#demo) !

## What ?

Silex Pack add some code structuring in your Silex project. It mimics the [Symfony Bundle](http://symfony.com/doc/current/cookbook/bundles/best_practices.html) feature.

### mountable pack

Packs can provides controllers and prefix where to mount.

Implement `Quazardous\Silex\Api\MountablePackInterface`


### twiggable pack

Packs can define private Twig templates folder with override capability from the application templates folder.

Implement `Quazardous\Silex\Api\TwiggablePackInterface`

For a pack class `AcmeDemoPack` Silex pack will register a `@AcmeDemo` namespace with Twig. `@AcmeDemo` is created from `PackInterface::getName()`.

Now you can use something like that in your controllers:

```php
...
return $app->renderView('@AcmeDemo/default/hello.html.twig', $vars);
...
```

Within `.twig` templates, you can also use `@AcmeDemo`, ie for `extends` clause:

```twig
{% extends '@AcmeDemo/base.html.twig' %}
...
```

If you register `twig.path`, Silex Pack will look for overriden templates in these folders.

```php
...
$app->register(new TwigServiceProvider(), ['twig.path' => '/path/to/app/views']);
...
```

For `@AcmeDemo/default/hello.html.twig` we will look for in `/path/to/app/views/AcmeDemo/default/hello.html.twig`. Cute no ?


### entitable pack

Packs can expose entites to Doctrine ORM.

Implement `Quazardous\Silex\Api\EntitablePackInterface`


### consolable pack

Packs can add commands to the console.

Implement `Quazardous\Silex\Api\ConsolablePackInterface`


### configurable pack

Packs can have config files. All the config files will be injected into the application container.

Implement `Quazardous\Silex\Api\ConfigurablePackInterface`


### assetable pack

Packs can have assets.

Implement `Quazardous\Silex\Api\AssetablePackInterface`

```twig
...
{% stylesheets '@AcmeDemo/css/*.css' output="css/acme_demo.css" %}
    <link href="{{ asset(asset_url) }}" type="text/css" rel="stylesheet" />
{% endstylesheets %}
...

```

You have to register the provided Assetic service provider because we have to inject a "namespace aware" assetic factory.

The provided `assetic.factory` knows how to handle paths with `@AcmeDemo` prefix.

`@AcmeDemo` is derived from `PackInterface::getName()`.

See `Quazardous\Silex\Provider\AsseticServiceProvider`

The assets dump is done in the standard `$app['assetic.path_to_web']`.

See [Silex Assetic](https://github.com/mheap/Silex-Assetic) from more info on this provider.

### translatable pack

Packs can have translations.

Implement `Quazardous\Silex\Api\TranslatablePackInterface`

You can provide yaml files, xliff files or php files (returning a key => translation array).


### linkable pack

You can create symlinks between project and pack (ie. for public files).

Implement `Quazardous\Silex\Api\LinkablePackInterface`

You'll have to execute the provided command `pack:symlinks`.


### optionnable pack

You can inject common options into your pack.

Implement `Quazardous\Silex\Api\OptionnablePackInterface`

```php
...
$app->register(new AcmeDemoPack(), [
    'acme_demo.mount_prefix' => '/acme/demo',
    'acme_demo.entity_subnamespace' => 'Model',
]);
...
```

See below.


## Usage

### Install

    composer require quazardous/silex-pack

Use `Quazardous\Silex\PackableApplication` instead of `Silex\Application`.

Implements the interfaces you need and register your pack as a classic service provider.

```php
...
$app = new Quazardous\Silex\PackableApplication;
...
use Acme\DemoPack\AcmeDemoPack;
$app->register(new AcmeDemoPack());
...
```

Enjoy (or not) !

### Jet pack

Silex Pack provides a basic dropin trait implementation for the trivial functions:

Use `Quazardous\Silex\Pack\JetPackTrait`

And a all in one interface:

Implement `Quazardous\Silex\Pack\JetPackInterface`

So with `JetPackInterface` + `JetPackTrait` you should just have to provide some options: 


```php
...
$app->register(new AcmeDemoPack(), [
    'acme_demo.mount_prefix' => '/acme/demo',
    'entity_subnamespace' => 'Model',
]);
...
```

The user pack namespace `acme_demo.` is derived from `PackInterface::getName()` wich result is decamelize.

See `JetPackTrait::$packOptions` for a list of all options.


### Commands

Silex pack uses [Sillex Console](https://github.com/quazardous/silex-console).

Silex pack provides assetic commands:

- `assetic:dump` : dumps the assets
- `assetic:watch` : watches the assets ans dumps if modifications

Register `Quazardous\Silex\Provider\AsseticCommandsProvider`

Silex pack provides pack commands:

- `pack:symlinks` : create pack symlinks

Register `Quazardous\Silex\Provider\PackCommandsProvider`

## Pack folders

A pack has no strict structure but it could/should be very similar to bundle:

```
+-- Acme/ :
|   +-- AlphaPack/
|   |   +-- AcmeAlphaPack.php
|   |   +-- Command/
|   |   +-- Controller/
|   |   +-- Entity/
|   |   +-- assets/
|   |   +-- configs/
|   |   +-- fixtures/
|   |   +-- locales/
|   |   +-- public/
|   |   +-- views/
|   |
|   +-- BetaPack/
|
```

## Demo

See a [full working demo](https://github.com/quazardous/silex-pack-demo).

You can use it as a quick bootstrap for your project. 

Features Silex User Pack (see below).

## Some pack projects
- [Silex User Pack](http://github.com/quazardous/silex-user-pack): user security helper pack

