# silex-pack
Add bundle like system to Silex

## What ?

Silex Pack add some automation in the following stuff:

- mountable pack : packs can provides controllers and prefix where to mount
- twiggable pack : packs can define private Twig templates folder
- entitable pack : packs can expose entites to Doctrine
- consolable pack : packs can add commands to the console

You can find the corresponding interfaces in src/Silex/Api.

## Usage

Implements the interfaces you need and register your bundle as a classic service provider.

```php
...
use Acme\DemoPack\AcmeDemoPack;
$app->register(new AcmeDemoPack());
...
```

Silex Pack provides a basic dropin trait implementation for the trivial functions: src/Silex/Pack/JetPackTrait.php

## Demo

See the demo for more details (read demo/README).
