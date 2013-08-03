<?php
/**
 * National Characters filter class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Filter;

use Zend\Filter\AbstractFilter;

class RestoreNationalCharactersFromTinyMce extends AbstractFilter
{
    /**
     * Defined by Zend\Filter\FilterInterface
     *
     * Returns string $value
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        // Polish characters
        $value = str_replace(array('&Oacute;', '&oacute;'), array('Ó', 'ó'), $value);

        return $value;
    }
}
