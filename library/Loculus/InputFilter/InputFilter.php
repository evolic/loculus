<?php
/**
 * Extended input filter class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\InputFilter;

use Zend\InputFilter\InputFilter as ZendInputFilter;

/**
 * Extended input filter class
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 *
 */
class InputFilter extends ZendInputFilter
{
    public function __toString()
    {
        return 'Zend\InputFilter\InputFilter';
    }
}