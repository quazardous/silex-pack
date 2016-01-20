# silex-pack
Add bundle like system to Silex 2

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
{% stylesheets '@AcmeDemo/css/*.css' output="css/acme_demo.css" %} // NB: output="css/acme_demo.css" is mandatory for now...
    <link href="{{ asset(asset_url) }}" type="text/css" rel="stylesheet" />
{% endstylesheets %}
...

```

You have to register the provided Assetic service provider because we have to inject a "namespace aware" asset factory.

-> Quazardous\Silex\Provider\AsseticServiceProvider

The dump is done in the standard $app['assetic.path_to_web'].

See https://github.com/mheap/Silex-Assetic.

### translatable pack

Packs can have translations.

-> Quazardous\Silex\Api\TranslatablePackInterface

You can provide yaml files, xliff files or php files (returning a key => translation array).


### optionnable pack

You can inject common options into your pack.

see Usage bellow.


## Usage

### Register

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
]);
...
```

See JetPackTrait::$packOptions.


### Assetic commands

Silex pack provides assetic commands.

- assetic:dump : dumps the assets
- assetic:watch : watches the assets ans dumps if modifications

-> Quazardous\Silex\Provider\AsseticCommandsProvider


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
|   |   +-- views/
|   |
|   +-- BetaPack/
|
```

## Demo

See the demo for more details (read demo/README).
