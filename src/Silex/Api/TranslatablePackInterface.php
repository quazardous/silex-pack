<?php
namespace Quazardous\Silex\Api;

use Quazardous\Silex\Api\PackInterface;

/**
 * 
 * Allows packs to have translations.
 * 
 * @link http://silex.sensiolabs.org/doc/providers/translation.html
 */
interface TranslatablePackInterface extends PackInterface
{    
    /**
     * Returns the translations path.
     * This folder should contain translation files named:
     *  - <locale>.yml|xlf|php
     *  - <domain>.<locale>.yml|xlf|php
     *  @return string
     */
    public function getTranslationsPath();
    
}