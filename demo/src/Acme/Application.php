<?php
namespace Acme;

use Quazardous\Silex\PackableApplication;
use Silex\Application\TwigTrait;
use Silex\Application\UrlGeneratorTrait;

class Application extends PackableApplication {
    use TwigTrait;
    use UrlGeneratorTrait;
}