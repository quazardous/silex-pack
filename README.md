# silex-pack
Add bundle like system to Silex

- mountable pack : packs can provides controllers and where to mount them
- twiggable pack : packs can define Twig templates dir with a namespace

```php
// we register our demo pack:
// - this will mount all the controllers on the given prefix
// - this will register the pack with the given namespace in Twig
// - this will allow template override
$app->register(new AcmeDemoPack());
```

See the demo for more details (read demo/README).
