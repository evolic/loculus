<?php
/**
 * Boolean filter class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Filter;

use Zend\Filter;

/**
 * Boolean filter class
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 *
 */
class StringToBoolean extends Filter\AbstractFilter {

    /**
     * Filters input value
     *
     * @param string $value
     * @return boolean
     */
    public function filter($value) {
        switch ($value) {
            case 'true':
                return true;
            case 'false':
                return false;
            default:
                return (bool) $value;
        }
    }
}
