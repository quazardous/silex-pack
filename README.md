# silex-pack
Add bundle like system to Silex 2

There is a [demo project](#demo) !

## What ?

Silex Pack add some automation in the following stuff:

### mountable pack

Packs can provides controllers and prefix where to mount.

-> Quazardous\Silex\Api\MountablePackInterface

### twiggable pack

Packs can define private Twig templates folder with override capability from the application templates folder.

-> Quazardous\Silex\Api\TwiggablePackInterface

### entitable pack

Packs can expose entites to Doctrine ORM.

-> Quazardous\Silex\Api\EntitablePackInterface

### consolable pack

Packs can add commands to the console.

-> Quazardous\Silex\Api\ConsolablePackInterface

### configurable pack

Packs can have config files. All the config files will be injected into the application container.

-> Quazardous\Silex\Api\ConfigurablePackInterface

### assetable pack

Packs can have assets.

-> Quazardous\Silex\Api\AssetablePackInterface

```twig
...
{% stylesheets '@AcmeDemo/css/*.css' output="css/acme_demo.css" %}
    <link href="{{ asset(asset_url) }}" type="text/css" rel="stylesheet" />
{% endstylesheets %}
...

```

You have to register the provided Assetic service provider because we have to inject a "namespace aware" asset factory.

-> Quazardous\Silex\Provider\AsseticServiceProvider

The dump is done in the standard `$app['assetic.path_to_web']`.

See https://github.com/mheap/Silex-Assetic.

### translatable pack

Packs can have translations.

-> Quazardous\Silex\Api\TranslatablePackInterface

You can provide yaml files, xliff files or php files (returning a key => translation array).


### linkable pack

You can create symlinks between project and pack (ie. for public files).

-> Quazardous\Silex\Api\LinkablePackInterface

You'll have to execute the provided command `pack:symlinks`.

### optionnable pack

You can inject common options into your pack.

-> Quazardous\Silex\Api\OptionnablePackInterface

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

Use Quazardous\Silex\PackableApplication instead of Silex\Application.

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

-> Quazardous\Silex\Pack\JetPackTrait

And a all in one interface:

-> Quazardous\Silex\Pack\JetPackInterface

So with JetPackInterface + JetPackTrait you just have to provide some options: 


```php
...
$app->register(new AcmeDemoPack(), [
    'acme_demo.mount_prefix' => '/acme/demo',
    'entity_subnamespace' => 'Model',
]);
...
```

The user pack namespace `acme_demo.` is derived from `PackInterface::getName()` wich result is decamelize.

See `JetPackTrait::$packOptions`.


### Commands

Silex pack provides assetic commands:

- assetic:dump : dumps the assets
- assetic:watch : watches the assets ans dumps if modifications

-> Quazardous\Silex\Provider\AsseticCommandsProvider

Silex pack provides pack commands:

- pack:symlinks : create pack symlinks

-> Quazardous\Silex\Provider\PackCommandsProvider

## Pack folders

A pack has no strict structure but it should be very similar to bundle:

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

See a full working demo at:
https://github.com/quazardous/silex-pack-demo

You can use it as a quick bootstrap project. 

