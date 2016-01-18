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

## Usage

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

Silex Pack provides a basic dropin trait implementation for the trivial functions:

-> Quazardous\Silex\Pack\JetPackTrait

And a all in one interface:

-> Quazardous\Silex\Pack\JetPackInterface

## Pack folders

A pack has no strict structure but it should be very similar to bundle:

```
+-- Acme/ :
|   +-- AlphaPack/
|   |   +-- AcmeAlphaPack.php
|   |   +-- Command/
|   |   +-- Controller/
|   |   +-- Entity/
|   |   +-- fixtures/
|   |   +-- views/
|   |
|   +-- BetaPack/
|
```

## Demo

See the demo for more details (read demo/README).
