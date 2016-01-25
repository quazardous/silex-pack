<?php

namespace Quazardous\Silex\Application;

use Symfony\Component\Form\FormBuilder;

/**
 * Named Form trait.
 *
 */
trait NamedFormTrait
{
    /**
     * Creates and returns a named form builder instance.
     *
     * @param string                   $name
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     * @param string|FormTypeInterface $type    Type of the form
     *
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function namedForm($name, $data = null, array $options = array(), $type = null)
    {
        if (null === $type) {
            // BC with Symfony < 2.8
            $type = class_exists('Symfony\Component\Form\Extension\Core\Type\RangeType') ? 'Symfony\Component\Form\Extension\Core\Type\FormType' : 'form';
        }
    
        return $this['form.factory']->createNamedBuilder($name, $type, $data, $options);
    }
}
